<?php

namespace backend\controllers;

use Yii;
use common\models\Vote;
use common\models\SearchVote;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\VoteItem;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use common\models\Image;
use common\models\CommonUtil;

/**
 * VoteController implements the CRUD actions for Vote model.
 */
class VoteController extends Controller
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
     * Lists all Vote models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchVote();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vote model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider=new ActiveDataProvider([
            'query'=>VoteItem::find()->andWhere(['vote_id'=>$id])->orderBy('created_at desc'),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        
        $itemModel=new VoteItem();
        $itemModel->vote_id=$id;
        if($itemModel->load(yii::$app->request->post())){
            $photo=UploadedFile::getInstance($itemModel, 'photo');
            if($photo!=null){
                $basePath='../../upload/photo/';
                $path=date('Ymd').'/';
                if(!is_dir($basePath.$path)){
                    mkdir($basePath.$path);
                }
                $fileName=date('YmdHis').rand(0000, 9999).'.'.$photo->extension;
                $photo->saveAs($basePath.$path.$fileName);
                $itemModel->path=$path;
                $itemModel->photo=$fileName;                
                $thumbDir=$basePath.$path.'thumb/';
                if(!is_dir($thumbDir)){
                    mkdir($thumbDir);
                }
                $thumb=new Image($basePath.$path.$fileName, $thumbDir.$fileName);
                $thumb->thumb(120,120);
                $thumb->out();
            }
            $itemModel->created_at=time();
            if($itemModel->save()){
                $itemModel=new VoteItem();
                $itemModel->vote_id=$id;
                    return $this->render('view', [
                        'model' => $this->findModel($id),
                        'dataProvider'=>$dataProvider,
                        'itemModel'=>$itemModel
                    ]);       
            }
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider,
            'itemModel'=>$itemModel
        ]);
    }
    
    public function actionExportResult($vote_id){
        $vote=Vote::findOne(['vote_id'=>$vote_id]);
        $model=VoteItem::findAll(['vote_id'=>$vote_id]);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','选项');
        $resultExcel->getActiveSheet()->setCellValue('C1','票数');
        $resultExcel->getActiveSheet()->setCellValue('D1','时间');
       /*  $resultExcel->getActiveSheet()->setCellValue('E1','邮箱');
        $resultExcel->getActiveSheet()->setCellValue('F1','微信');
        $resultExcel->getActiveSheet()->setCellValue('G1','竞聘店面');
        $resultExcel->getActiveSheet()->setCellValue('H1','报名时间');
        $resultExcel->getActiveSheet()->setCellValue('I1','是否签到');
        $resultExcel->getActiveSheet()->setCellValue('J1','签到时间');
        $resultExcel->getActiveSheet()->setCellValue('K1','签到人'); */
    
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->content);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->vote_number);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,CommonUtil::fomatTime($v->updated_at));
            $i++;
        }        
        //设置导出文件名
        $outputFileName =$vote->title."-投票结果".date('Y-m-d',time()).'.xls';
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
     * Creates a new Vote model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vote();
        if ($model->load(Yii::$app->request->post()) ) {
            $photo=UploadedFile::getInstanceByName('photo');
            if(!empty($photo)){                        
            $basePath='../../upload/photo/';
            $path=date('Ymd').'/';
            if(!is_dir($basePath.$path)){
                mkdir($basePath.$path);
             }
            $fileName=date('YmdHis').rand(0000, 9999).'.'.$photo->extension;
            $photo->saveAs($basePath.$path.$fileName);
            $model->path=$path;
            $model->photo=$fileName;
            
            $thumbDir=$basePath.$path.'thumb/';
            if(!is_dir($thumbDir)){
                mkdir($thumbDir);
            }
            if(is_file($basePath.$path.$fileName)){
                $thumb=new Image($basePath.$path.$fileName, $thumbDir.$fileName);
                $thumb->thumb(150,200);
                $thumb->out();
            }
            
            $standardDir=$basePath.$path.'standard/';
            if(!is_dir($standardDir)){
                mkdir($standardDir);
            }
            if(is_file($basePath.$path.$fileName)){
                $thumb=new Image($basePath.$path.$fileName, $standardDir.$fileName);
                $thumb->thumb(640,300);
                $thumb->out();
            }
                               
             }
            $model->created_at=time();
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->vote_id]);
            }else{
                yii::$app->getSession()->setFlash('error','提交失败,服务器错误!');
                 return $this->render('create', [
                'model' => $model,
              ]);
            }
          
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Vote model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->start_time=CommonUtil::fomatTime($model->start_time);
        $model->end_time=CommonUtil::fomatTime($model->end_time);
        if ($model->load(Yii::$app->request->post()) ) {    
            
            $photo=UploadedFile::getInstanceByName( 'photo');
            if(!empty($photo)){
                $basePath='../../upload/photo/';
                $path=date('Ymd').'/';
                if(!is_dir($basePath.$path)){
                    mkdir($basePath.$path);
                }
                $fileName=date('YmdHis').rand(0000, 9999).'.'.$photo->extension;
                $photo->saveAs($basePath.$path.$fileName);
                $model->path=$path;
                $model->photo=$fileName;
            
                $thumbDir=$basePath.$path.'thumb/';
                if(!is_dir($thumbDir)){
                    mkdir($thumbDir);
                }
                if(is_file($basePath.$path.$fileName)){
                    $thumb=new Image($basePath.$path.$fileName, $thumbDir.$fileName);
                    $thumb->thumb(150,200);
                    $thumb->out();
                }
            
                $standardDir=$basePath.$path.'standard/';
                if(!is_dir($standardDir)){
                    mkdir($standardDir);
                }
                if(is_file($basePath.$path.$fileName)){
                    $thumb=new Image($basePath.$path.$fileName, $standardDir.$fileName);
                    $thumb->thumb(640,300);
                    $thumb->out();
                }
                 
            }
            
            $model->updated_at=time();
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->vote_id]);
            }else{
                yii::$app->getSession()->setFlash('error','提交失败,服务器错误!');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Vote model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteItem($id)
    {
        $item=VoteItem::findOne($id);
        $vote_id=$item->vote_id;
        $item->delete();
    
        return $this->redirect(['view','id'=>$vote_id]);
    }

    /**
     * Finds the Vote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
