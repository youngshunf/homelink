<?php

namespace frontend\controllers;

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
use common\models\User;
use common\models\AuthUser;

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
         $searchModel->pid=$user->pid;
        
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
                return $this->redirect(['view','id'=>$model->reportid]);
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
    
    public function actionAnswerQuestion($id)
    {
        $user=yii::$app->user->identity;
        $question=ReportQuestion::findOne($id);
        $reportRelation=ReportRelation::findAll(['reportid'=>$question->reportid,'do_work_number'=>$user->work_number,'type'=>$question->type,'is_answer'=>'0']);
        if(empty($reportRelation) || count($reportRelation)<=0){
            yii::$app->getSession()->setFlash('error','您没有未评价的用户!');
            return $this->redirect(yii::$app->request->referrer);
        }
        $res=[];
        foreach ($reportRelation as $k=>$v){
            $authUser=AuthUser::findOne(['work_number'=>$v->work_number]);
            if(empty($authUser)){
                continue;
            }
            $res[$k]['key']=$k;
            $res[$k]['reportid']=$v->reportid;
            $res[$k]['work_number']=$v->work_number;
            $res[$k]['do_work_number']=$v->do_work_number;
            $res[$k]['name']=$authUser->name;
            $res[$k]['type']=$v->type;
            $res[$k]['value']='';
            $res[$k]['option'][]=[
                'name'=>'非常符合',
                'value'=>'5'
            ];
            $res[$k]['option'][]=[
                'name'=>'比较符合',
                'value'=>'4'
            ];
            $res[$k]['option'][]=[
                'name'=>'一般符合',
                'value'=>'3'
            ];
            $res[$k]['option'][]=[
                'name'=>'不符合',
                'value'=>'2'
            ];
            $res[$k]['option'][]=[
                'name'=>'完全不符合',
                'value'=>'1'
            ];
        }
        $reportRelation=Json::encode($res);
        return $this->render('answer-question',['question'=>$question,'reportRelation'=>$reportRelation]);
    }
    
    public function actionSubmitAnswer(){
        $answerArr=@$_POST['answer'];
        if(empty($answerArr)){
            yii::$app->getSession()->setFlash('error','未收到答案');
            return $this->redirect(yii::$app->request->referrer);
        }
        if(is_string($answerArr)){
            $answerArr=json_decode($answerArr,true);
        }
        foreach ($answerArr as $v){
            $relation=ReportRelation::findOne(['reportid'=>$v['reportid'],'work_number'=>$v['work_number'],'type'=>$v['type'],'do_work_number'=>$v['do_work_number']]);
            if(!empty($relation)){
                $relation->answer=json_encode($v['answer']);
                $relation->answer_time=time();
                $relation->updated_at=time();
                $relation->is_answer=1;
                if(!$relation->save()){
                    yii::$app->getSession()->setFlash('error','答案提交失败!');
                    return $this->redirect(yii::$app->request->referrer);
                }
            }
        }
        
        yii::$app->getSession()->setFlash('success','提交成功!');
        return $this->redirect(['index']);
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