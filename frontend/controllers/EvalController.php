<?php

namespace frontend\Controllers;

use Yii;
use common\models\Question;
use common\models\SearchQuestion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Option;
use yii\data\ActiveDataProvider;
use common\models\Evaluation;
use frontend\models\LoginForm;
use common\models\EvalRelation;
use common\models\AuthUser;
use common\models\Answer;

/**
 * EvalController implements the CRUD actions for Question model.
 */
class EvalController extends Controller
{
    public $enableCsrfValidation = false;
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
    
    public function beforeAction($action){
         
        yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        
        if(!yii::$app->user->isGuest){
            if(yii::$app->user->identity->is_auth==0){
                return $this->redirect(['site/no-auth']);
            }
        }
        
        return  parent::beforeAction($action);
    }
    
    /**
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
           if(yii::$app->user->identity->is_auth==0){
            return $this->redirect(['site/no-auth']);
        }
        
        $dataProvider=new ActiveDataProvider([
            'query'=>EvalRelation::find() ->andWhere(['up_work_number'=>yii::$app->user->identity->work_number]),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        
        return $this->render('index',[
            'dataProvider'=>$dataProvider
        ]);
   }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($workNumber)
    {
        $model=Question::find()->orderBy('created_at desc')->one();
        $option="";
        if(!empty($model)){
            $option=Option::findAll(['qid'=>$model->qid]);
        }
        $user=AuthUser::findOne(['work_number'=>$workNumber]);
        
        return $this->render('view', [
            'model' => $model,
            'option'=>$option,
            'workNumber'=>$workNumber,
            'user'=>$user
        ]);
    }
    
    public function actionEvalDo(){
        if(!isset($_POST['data'])){
            return "fail";
        }
        $data=$_POST['data'];
      //  print_r($data);die;
        $eval_work_number=$_POST['workNumber'];
        $work_number=yii::$app->user->identity->work_number;
        $user_guid=yii::$app->user->identity->user_guid;
        $qid=$_POST['qid'];
       // $data=json_decode($data,true);
        foreach ($data as $v){
            $answer=new Answer();
            $answer->qid=$v['qid'];
            $answer->oid=$v['oid'];
            $answer->answer=$v['answer'];
            $answer->work_number=$work_number;
            $answer->eval_work_number=$eval_work_number;
            $answer->created_at=time();
           if(!$answer->save()){
               return "fail";
           } 
        }
        
        $evaluation=new Evaluation();
        $evaluation->user_guid=$user_guid;
        $evaluation->work_number=$work_number;
        $evaluation->eval_work_number=$eval_work_number;
        $evaluation->qid=$qid;
        $evaluation->created_at=time();
        if ($evaluation->save()){
           yii::$app->getSession()->setFlash('success','评价成功!');
            return $this->redirect('index');
        }       
            return "fail";
    }
    
    
    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
        
        
        

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    
    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
