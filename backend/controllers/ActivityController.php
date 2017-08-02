<?php

namespace backend\Controllers;

use Yii;
use common\models\Activity;
use common\models\SearchActivity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\Image;
use yii\data\ActiveDataProvider;
use common\models\ActivityRegister;
use common\models\CommonUtil;
use common\models\ImageUploader;
use yii\filters\AccessControl;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchActivity();
        $searchModel->typeFlag=2;
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
    
    public function actionExportRegister($activity_id){
        $activity=Activity::findOne(['activity_id'=>$activity_id]);
        $model=ActivityRegister::findAll(['activity_id'=>$activity_id]);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','还没有人报名,没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','工号');
        $resultExcel->getActiveSheet()->setCellValue('C1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('D1','电话');
        $resultExcel->getActiveSheet()->setCellValue('E1','邮箱');
        $resultExcel->getActiveSheet()->setCellValue('F1','微信');
        $resultExcel->getActiveSheet()->setCellValue('G1','竞聘店面');
        $resultExcel->getActiveSheet()->setCellValue('H1','报名时间');
        $resultExcel->getActiveSheet()->setCellValue('I1','是否签到');
        $resultExcel->getActiveSheet()->setCellValue('J1','签到时间');
        $resultExcel->getActiveSheet()->setCellValue('K1','签到人');
 
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->work_number);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->name);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$v->mobile);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->email);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$v->weixin);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->sign_shop);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,CommonUtil::fomatTime($v->created_at));
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$v->is_sign==0?"否":"是");
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,CommonUtil::fomatTime($v->sign_time));
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$v['manager']['real_name']);
    
            $i++;
        }
         
        //设置导出文件名
        $outputFileName =$activity->title."-报名结果".date('Y-m-d',time()).'.xls';
        $xlsWriter = new \PHPExcel_Writer_Excel5($resultExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    
        $xlsWriter->save( "php://output" );
         
    
    
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider=new ActiveDataProvider([
            'query'=>ActivityRegister::find()->andWhere(['activity_id'=>$id])->orderBy('created_at desc'),
            'pagination'=>[
                'pagesize'=>10
            ]
            
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider
        ]);
    }
    

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activity();

        if ($model->load(Yii::$app->request->post())  ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->sign_end_time=strtotime($model->sign_end_time);
            $model->sign_start_time=strtotime($model->sign_start_time);
            $model->year_month=date("Ym",$model->start_time);
            $user=yii::$app->user->identity;
            if($user->role_id==98){
                $model->pid=$user->id;
            }
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
                ImageUploader::uploadPhotoThumb('photoThumb', $model->path, $model->photo);
            }
                   
            $model->content=@$_POST['activity-content'];
            $model->created_at=time();
            
            if(!$model->save()){
                yii::$app->getSession()->setFlash('success','活动发布失败,请重试!');
            }
            return $this->redirect(['view', 'id' => $model->activity_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       $model->start_time=CommonUtil::fomatTime($model->start_time);
       $model->end_time=CommonUtil::fomatTime($model->end_time);
       $model->sign_end_time=CommonUtil::fomatTime($model->sign_end_time);
       $model->sign_start_time=CommonUtil::fomatTime($model->sign_start_time);
       
        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->sign_end_time=strtotime($model->sign_end_time);
            $model->sign_start_time=strtotime($model->sign_start_time);
            $model->year_month=date("Ym",$model->start_time);

            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            
            $model->content=$_POST['activity-content'];
            $model->updated_at=time();
            
            if(!$model->save()){
                yii::$app->getSession()->setFlash('success','活动更新失败,请重试!');
            }
            return $this->redirect(['view', 'id' => $model->activity_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionTop($id)
    {
        $model=$this->findModel($id);
        if($model->is_top==0){
            $model->is_top=1;
        }else{
            $model->is_top=0;
        }
        if($model->save()){
            yii::$app->getSession()->setFlash('success','操作成功!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
