<?php

namespace backend\Controllers;

use Yii;
use common\models\Task;
use common\models\SearchTask;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ImageUploader;
use yii\data\ActiveDataProvider;
use common\models\TaskResult;
use common\models\GrowthRec;
use yii\web\UploadedFile;
use common\models\CommonUtil;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchTask();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionTaskResult($id){
        $dataProvider=new ActiveDataProvider([
            'query'=>TaskResult::find()->andWhere(['task_id'=>$id])->orderBy('created_at desc')
        ]);
        
        return $this->render('task-result',[
            'dataProvider'=>$dataProvider,
            'task_id'=>$id
        ]);
    }
    
    public function actionExportResult($id){
       
             
        $model=TaskResult::find()->andWhere(['task_id'=>$id])->orderBy('created_at desc')->all();
      
        $task=Task::findOne($id);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','没有数据哦');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
        
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('C1','工号');
        $resultExcel->getActiveSheet()->setCellValue('D1','所属大区');
        $resultExcel->getActiveSheet()->setCellValue('E1','获得分数');
        $resultExcel->getActiveSheet()->setCellValue('F1','评价');
        $resultExcel->getActiveSheet()->setCellValue('G1','评论用户');
        $resultExcel->getActiveSheet()->setCellValue('H1','评论时间');
      
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->user->real_name);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->work_number);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$v->business_district);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->score);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$v->comment);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,@$v->commentUser->real_name);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,CommonUtil::fomatTime($v->created_at));
            $i++;
        }
         
        //设置导出文件名
        $outputFileName =$task->name."结果导出".date('Y-m-d',time()).'.xls';
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
    
    public function actionGrow(){
        $dataProvider=new ActiveDataProvider([
            'query'=>GrowthRec::find()->orderBy('created_at desc'),
        ]);
        return $this->render('grow',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionDeleteGrow($id){
        GrowthRec::findOne($id)->delete();
        yii::$app->getSession()->setFlash('success','删除成功');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    //导入成长记录
    public function actionImportGrowdata()
    {
        $file=UploadedFile::getInstanceByName('relation');
        if(!isset($file)){
            yii::$app->getSession()->setFlash('error','文件上传失败,请重试');
            return $this->redirect('index');
        }
        if ($file->extension!='xls'&&$file->extension!='xlsx'){
            yii::$app->getSession()->setFlash('error','导入失败,请上传excel文件');
            return $this->redirect('index');
        }
        $objPHPExcel = \PHPExcel_IOFactory::load($file->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
    
        $result = 0;
        $irecord = 0;
    
    
        foreach ($sheetData as $record)
        {
            $irecord++;
            if($irecord<2){
                continue;
            }
             
            $growRec=new GrowthRec();
            $growRec->work_number=trim($record['A']);
            $growRec->item_time=trim($record['B']);
            $growRec->items=trim($record['C']);
            $growRec->score=trim($record['D']);
            $growRec->classname=trim($record['E']);
            $growRec->created_at=time();
            if($growRec->save()){
                $result++;
            }
        }
        yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
        return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post())) {
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
             
            $model->requirement=$_POST['requirement'];
            $model->created_at=time();
            if(  $model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
             
            $model->requirement=$_POST['requirement'];
            $model->updated_at=time();
            if(  $model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Task model.
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
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
