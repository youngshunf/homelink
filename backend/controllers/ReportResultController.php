<?php

namespace backend\controllers;

use Yii;
use common\models\ReportResult;
use common\models\SearchReportResult;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\ImageUploader;
use common\models\CommonUtil;
use yii\web\UploadedFile;
use common\models\Unzip;

/**
 * ReportResultController implements the CRUD actions for ReportResult model.
 */
class ReportResultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all ReportResult models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchReportResult();
        $user=yii::$app->user->identity;
        if($user->role_id==98){
            $searchModel->pid=$user->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReportResult model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReportResult model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReportResult();

        if ($model->load(Yii::$app->request->post()) ) {
            $user=yii::$app->user->identity;
            if($user->role_id==98){
                $model->pid=$user->id;
            }
            $model->created_at=time();
            $model->report_time=strtotime($model->report_time);
            $ruser=User::findOne(['work_number'=>$model->work_number]);
            if(!empty($ruser)){
                $model->user_guid=$ruser->user_guid;
            }
            $file=ImageUploader::uploadFile('file');
            if($file){
                $model->path=$file['path'];
                $model->photo=$file['photo'];
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                yii::$app->getSession()->setFlash('上传失败!');
            }
           
        } 
            return $this->render('create', [
                'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing ReportResult model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->report_time=CommonUtil::fomatTime($model->report_time);
        if ($model->load(Yii::$app->request->post()) ) {
            $model->updated_at=time();
            $model->report_time=strtotime($model->report_time);
            $ruser=User::findOne(['work_number'=>$model->work_number]);
            if(!empty($ruser)){
                $model->user_guid=$ruser->user_guid;
            }
            $file=ImageUploader::uploadFile('file');
            if($file){
                $model->path=$file['path'];
                $model->photo=$file['photo'];
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                yii::$app->getSession()->setFlash('更新失败!');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionImportReport()
    {
        $file=UploadedFile::getInstanceByName('file');
        if(!isset($file)){
            yii::$app->getSession()->setFlash('error','文件上传失败,请重试');
            return $this->redirect('index');
        }
        if ($file->extension!='xls'&&$file->extension!='xlsx'){
            yii::$app->getSession()->setFlash('error','导入失败,请上传excel文件');
            return $this->redirect('index');
        }
        
        $zipfile=UploadedFile::getInstanceByName('zip');
        if(!isset($zipfile)){
            yii::$app->getSession()->setFlash('error','文件上传失败,请重试');
            return $this->redirect('index');
        }
        if ($zipfile->extension!='zip'){
            yii::$app->getSession()->setFlash('error','导入失败,请上传zip文件');
            return $this->redirect('index');
        }
        $pathName=date('Ymd').'/';
       
        $basePath='../../upload/file/';
        $distPath=$basePath.$pathName;
        if(!is_dir($distPath)){
            mkdir($distPath);
        }
        $z=new Unzip();
       $unzip=$z->unzip($zipfile->tempName,$basePath.$pathName,true,false);
       if(!$unzip){
           yii::$app->getSession()->setFlash('error','解压文件失败!');
           return $this->redirect('index');
       }
        $objPHPExcel = \PHPExcel_IOFactory::load($file->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
        
        $result = 0;
        $irecord = 0;
       
        
        
        
        foreach ($sheetData as $k=>$record)
        {
            if($k<2){
                continue;
            }
            if(empty($record['A'])){
                continue;
            }
            $work_number=trim($record['A']);
            $reportResult=new ReportResult();
            $reportResult->work_number=$work_number;
            $ruser=User::findOne(['work_number'=>$work_number]);
            if(!empty($ruser)){
                $reportResult->user_guid=$ruser->user_guid;
            }
            $reportResult->name=trim($record['B']);
            if(empty(trim($record['C']))){
                $reportResult->report_time=time();
            }else{
                $reportResult->report_time=strtotime(trim($record['C']));
            }
            $reportResult->path=$pathName;
            $reportResult->photo=trim($record['D']);
            $reportResult->created_at=time();
            
           if($reportResult->save()){
               $result++;
           }
            
        }
        
        
        yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
        return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * Deletes an existing ReportResult model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReportResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReportResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReportResult::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
