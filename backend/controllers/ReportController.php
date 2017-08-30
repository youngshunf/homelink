<?php

namespace backend\controllers;

use Yii;
use common\models\Report;
use common\models\SearchReport;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ReportLevel;
use yii\data\ActiveDataProvider;
use common\models\ReportTarget;
use common\models\ReportQuestion;
use common\models\ReportRelation;
use yii\helpers\Json;
use yii\web\UploadedFile;
use common\models\CommonUtil;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
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
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchReport();
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
     * Displays a single Report model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $questionData=new ActiveDataProvider([
            'query'=>ReportQuestion::find()->andWhere(['reportid'=>$id])->orderBy('type asc')
        ]);
        $relationData=new ActiveDataProvider([
            'query'=>ReportRelation::find()->andWhere(['reportid'=>$id])
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'questionData'=>$questionData,
            'relationData'=>$relationData
        ]);
    }
    
    public function actionCreateQuestion($id){
        $model=new ReportQuestion();
        $user=yii::$app->user->identity;
        $model->reportid=$id;
        $model->pid=$user->id;
        $model->question='[]';
        if ($model->load(Yii::$app->request->post()) ) {
            $model->question=$_POST['question'] ;
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['view','id'=>$id]);
            }else{
                yii::$app->getSession()->setFlash('error','创建问卷失败!');
            }
        }
      
        $report=Report::findOne($id);
        $targets=ReportTarget::findAll(['pid'=>$user->id]);
        $targets=Json::encode($targets);
        return $this->render('edit-question',[
            'model'=>$model,
            'report'=>$report,
            'targets'=>$targets
        ]);
    }
    public function actionUpdateQuestion($id){
        $model=ReportQuestion::findOne($id);
        if ($model->load(Yii::$app->request->post()) ) {
            $model->question=$_POST['question'];
            $model->updated_at=time();
            if($model->save()){
                return $this->redirect(['view','id'=>$id]);
            }else{
                yii::$app->getSession()->setFlash('error','更新问卷失败!');
            }
        }
        $user=yii::$app->user->identity;
        $report=Report::findOne($model->reportid);
        $targets=ReportTarget::findAll(['pid'=>$user->id]);
        $targets=Json::encode($targets);
        return $this->render('edit-question',[
            'model'=>$model,
            'report'=>$report,
            'targets'=>$targets
        ]);
    }
    public function actionDeleteQuestion($id){
        ReportQuestion::findOne($id)->delete();
        yii::$app->getSession()->setFlash('success','删除成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    public function actionSetting(){
        $user=yii::$app->user->identity;
        if($user->role_id==98){
            $level=ReportLevel::findOne(['pid'=>$user->id]);
            if(empty($level)){
                $level=new ReportLevel();
                $level->pid=$user->id;
                $level->up=5;
                $level->down=3;
                $level->same=1;
                $level->self=1;
                $level->created_at=time();
                $level->save();
            }
            $levelData=new ActiveDataProvider([
                'query'=>ReportLevel::find()->andWhere(['pid'=>$user->id])
            ]);
            $targetData=new ActiveDataProvider([
                'query'=>ReportTarget::find()->andWhere(['pid'=>$user->id])
            ]);
        }else{
            $levelData=new ActiveDataProvider([
                'query'=>ReportLevel::find()->orderBy('created_at desc')
            ]);
            $targetData=new ActiveDataProvider([
                'query'=>ReportTarget::find()->orderBy('pid desc')
            ]);
        }
        
        
        
        return $this->render('setting',[
            'levelData'=>$levelData,
            'targetData'=>$targetData
        ]);
    }

    /**
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Report();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->report_time=strtotime($model->report_time);
            $model->desc=@$_POST['desc'];
            $user=yii::$app->user->identity;
            if($user->role_id==98){
                $model->pid=$user->id;
            }
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','新增失败!');
            }
            
        } 
        
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Report model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->report_time=strtotime($model->report_time);
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','新增失败!');
            }
        } 
            return $this->render('update', [
                'model' => $model,
            ]);
        
    }
    
    public function actionUpdateLevel($id){
        $model=ReportLevel::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['setting']);
        }
        return $this->render('update-level',[
            'model'=>$model
        ]);
    }
    
    public function actionCreateTarget(){
        $model=new ReportTarget();
        if ($model->load(Yii::$app->request->post()) ) {
            $user=yii::$app->user->identity;
            $model->pid=$user->id;
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['setting']);
            }else{
                yii::$app->getSession()->setFlash('error','新增失败!');
            }
            
        }
        return $this->render('create-target',[
            'model'=>$model
        ]);
    }
    
    public function actionUpdateTarget($id){
        $model=ReportTarget::findOne($id);
        if ($model->load(Yii::$app->request->post()) ) {
            if($model->save()){
                return $this->redirect(['setting']);
            }else{
                yii::$app->getSession()->setFlash('error','修改失败!');
            }
        }
        return $this->render('update-target',[
            'model'=>$model
        ]);
    }
    
    public function actionDeleteTarget($id){
       ReportTarget::findOne($id)->delete();
       yii::$app->getSession()->setFlash('success','删除成功!');
       return $this->redirect(['setting']);
    }
    
    public function actionImportRelation()
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
        $objPHPExcel = \PHPExcel_IOFactory::load($file->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
        
        $result = 0;
        $irecord = 0;
        $reportid=@$_POST['reportid'];
        
        foreach ($sheetData as $k=>$record)
        {
            if($k<3){
                continue;
            }
            if(empty($record['A'])){
                continue;
            }
            $work_number=trim($record['A']);
            $typeStr=trim($record['C']);
            $type=CommonUtil::getReportType($typeStr);
            $do_work_number=trim($record['B']);
            $relation=new ReportRelation();
            $relation->reportid=$reportid;
            $relation->work_number=$work_number;
            $relation->do_work_number=$do_work_number;
            $relation->type=$type;
            $relation->created_at=time();
            if($relation->save()){
                $result++;
            }
        }
        
        
        yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionDeleteRelation($id){
        ReportRelation::findOne($id)->delete();
        yii::$app->getSession()->setFlash('success','删除成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionViewAnswer($id){
        $model=ReportRelation::findOne($id);
        if(empty($model->answer)){
            yii::$app->getSession()->setFlash('error','还未提交测评结果!');
            return $this->redirect(yii::$app->request->referrer);
        }
        return $this->render('view-answer',['model'=>$model]);
    }

    /**
     * Deletes an existing Report model.
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
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
