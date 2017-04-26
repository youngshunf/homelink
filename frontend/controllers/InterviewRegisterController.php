<?php

namespace frontend\controllers;

use Yii;
use common\models\InterviewRegister;
use common\models\SearchInterviewRegister;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\InterviewPhoto;
use common\models\ImageUploader;

/**
 * InterviewRegisterController implements the CRUD actions for InterviewRegister model.
 */
class InterviewRegisterController extends Controller
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
            if(yii::$app->user->identity->role_id!=89 && yii::$app->user->identity->role_id!=88){
                return $this->redirect(['site/permission-deny']);
            }
        }
        
        return  parent::beforeAction($action);
    } 

    /**
     * Lists all InterviewRegister models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user=yii::$app->user->identity;
        $searchModel = new SearchInterviewRegister();
        $searchModel->district_code=$user->district_code;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model=InterviewPhoto::findOne(['district_code'=>$user->district_code,'year_month'=>date('Ym')]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }

    public function actionUploadPhoto(){
        $user=yii::$app->user->identity;
        $year_month=date('Ym');
        $type=$_POST['type'];
            $model=new InterviewPhoto();
            $model->year_month=$year_month;
            $model->created_at=time();
            $model->district_code=$user->district_code;
            $model->district_name=$user->district_name;
            $model->type=$type;
        $photo=ImageUploader::uploadByName('photo');
        if($photo){
            $model->path=$photo['path'];
            $model->photo=$photo['photo'];
            $model->user_guid=$user->user_guid;
            $model->work_number=$user->work_number;
            $model->name=$user->real_name;
            if($model->save()){
                yii::$app->getSession()->setFlash('success','图片上传成功!');
            }else{
                yii::$app->getSession()->setFlash('error','图片上传失败!');
            }
            return $this->redirect(yii::$app->request->referrer);
        }
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionUploadPhotose(){
        $user=yii::$app->user->identity;
        $year_month=date('Ym');
        $model=InterviewPhoto::findOne(['district_code'=>$user->district,'year_month'=>$year_month]);
        if(empty($model)){
            $model=new InterviewPhoto();
            $model->year_month=$year_month;
            $model->created_at=time();
            $model->district_code=$user->district_code;
            $model->district_name=$user->district_name;
        }
        $photo=ImageUploader::uploadByName('photo1');
        if($photo){
            $model->path1=$photo['path'];
            $model->photo1=$photo['photo'];
            $model->user_guid=$user->user_guid;
            $model->work_number=$user->work_number;
            $model->name=$user->real_name;
            if($model->save()){
                yii::$app->getSession()->setFlash('success','图片上传成功!');
            }else{
                yii::$app->getSession()->setFlash('error','图片上传失败!');
            }
            return $this->redirect(yii::$app->request->referrer);
        }
        return $this->redirect(yii::$app->request->referrer);
    }
    /**
     * Displays a single InterviewRegister model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPass($id){
        InterviewRegister::updateAll(['interview_result'=>'1'],['id'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    public function actionDeny(){
        $id=$_POST['id'];
        $remark=$_POST['remark'];
        $status=$_POST['status'];
        InterviewRegister::updateAll(['interview_result'=>$status,'remark'=>$remark],['id'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionAppeal($id){
        InterviewRegister::updateAll(['is_appeal'=>'1'],['id'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    /**
     * Creates a new InterviewRegister model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InterviewRegister();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InterviewRegister model.
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
     * Deletes an existing InterviewRegister model.
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
     * Finds the InterviewRegister model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InterviewRegister the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InterviewRegister::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
