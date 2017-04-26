<?php

namespace frontend\Controllers;

use Yii;
use common\models\ResultsData;
use common\models\SearchResultsData;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ResultRelation;
use yii\data\ActiveDataProvider;
use frontend\models\LoginForm;
use yii\filters\AccessControl;

/**
 * DataController implements the CRUD actions for ResultsData model.
 */
class DataController extends Controller
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
     * Lists all ResultsData models.
     * @return mixed
     */

    
    public function actionIndex()
    {
        $searchModel = new SearchResultsData();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * 查询我的数据
     * @author youngshunf
     *
     */
    public function actionMyData(){
        
    if(yii::$app->user->identity->is_auth==0){
            return $this->redirect(['site/no-auth']);
        }               
        return $this->redirect(['view','workNumber'=>yii::$app->user->identity->work_number]);
    }
    
    public function actionQueryData(){
        //登录检查
    if(yii::$app->user->identity->is_auth==0){
            return $this->redirect(['site/no-auth']);
        }
        
        $dataProvider=new ActiveDataProvider([
            'query'=>ResultRelation::find() ->andWhere(['up_work_number'=>yii::$app->user->identity->work_number]),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        
        return $this->render('index',[
            'dataProvider'=>$dataProvider
        ]);
    }

    /**
     * Displays a single ResultsData model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($workNumber)
    {
        if (isset($_GET['yearMonth'])){
            $yearMonth=$_GET['yearMonth'];
            $model=ResultsData::findOne(['work_number'=>$workNumber,'year_month'=>$yearMonth]);
        }else{
             $model=ResultsData::find()->andWhere(['work_number'=>$workNumber])->orderBy("year_month desc")->one();
        }
    
        return $this->render('view', [
            'model' =>$model,
        ]);
    }
    
    

    /**
     * Updates an existing ResultsData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
  
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ResultsData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResultsData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ResultsData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
