<?php

namespace frontend\Controllers;

use Yii;
use common\models\Activity;
use common\models\SearchActivity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use common\models\Image;
use yii\data\ActiveDataProvider;
use common\models\ActivityRegister;
use common\models\CommonUtil;
use common\models\User;

use dosamigos\qrcode\QrCode;
use dosamigos\qrcode\lib\Enum;
use yii\filters\AccessControl;
use common\models\Card;
use common\models\InterviewDistrict;
use common\models\InterviewRegister;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
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
     * 普通活动
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchActivity();
        $searchModel->is_top=0;
        $user=yii::$app->user->identity;
        $searchModel->pFlag=1;
        $searchModel->pid=$user->pid;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $yearMonth=date("Y-m");
        $topData =new ActiveDataProvider([
            'query'=>Activity::find()->andWhere(['is_top'=>1,'pid'=>$user->pid])->orderBy("start_time desc"),
        ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'topData' => $topData,
            'yearMonth'=>$yearMonth
        ]);
    }
    
    public function actionSearchTime()
    {
        $yearMonth=date("Ym",strtotime($_POST['time']));
        $dataProvider =new ActiveDataProvider([
            'query'=>Activity::find()->andWhere(['year_month'=>$yearMonth,'type'=>0])->orderBy("start_time desc"),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);

        return $this->render('index', [    
            'dataProvider' => $dataProvider,
            'yearMonth'=>$yearMonth
        ]);
    }
    
    /**
     * 竞聘活动
     */
    
    public function actionRace(){
        $searchModel = new SearchActivity();
        $searchModel->typeFlag=1;
        $searchModel->is_top=0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $yearMonth=date("Ym");
        $topData =new ActiveDataProvider([
            'query'=>Activity::find()->andWhere(['year_month'=>$yearMonth,'type'=>1,'type'=>'3','is_top'=>'1'])->orderBy("start_time desc"),
        ]);
        return $this->render('race', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'topData'=>$topData,
            'yearMonth'=>date("Y-m")
        ]);
    }
    
    public function actionRaceSearchTime()
    {
        $yearMonth=date("Ym",strtotime($_POST['time']));
        $dataProvider =new ActiveDataProvider([
            'query'=>Activity::find()->andWhere(['year_month'=>$yearMonth,'type'=>1,'type'=>'3'])->orderBy("start_time desc"),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
    
        return $this->render('race', [
            'dataProvider' => $dataProvider,
            'yearMonth'=>$yearMonth
        ]);
    }
    
    

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        
        $user_guid=yii::$app->user->identity->user_guid;
        $user=User::findOne(['user_guid'=>$user_guid]);
        $model= $this->findModel($id);
        $work_number=yii::$app->user->identity->work_number;
        if(empty($model->qrcode)){
            //二维码生成
            $qrPath='../../upload/photo/qrcode/sign/';
            if(!is_dir($qrPath)){
                mkdir($qrPath);
            }
            $qrName='sign'.date("YmdHis").rand(1000, 9999).'.png';
            $qrFile=$qrPath.$qrName;
            $id=$model->activity_id;
            if(!file_exists($qrFile)){
                QrCode::png(yii::$app->urlManager->createAbsoluteUrl('activity/sign')."?activity_id=$id",$qrFile,Enum::QR_ECLEVEL_H,7);
            }
            $model->qrcode=$qrName;
            $model->save();
        }
        if($model->type!=3){
        $registerModel=ActivityRegister::findOne(['activity_id'=>$id,'work_number'=>$work_number]);
        if(!empty($registerModel)){
            if($registerModel->is_sign==0){
               
              return $this->redirect(['view-register','id'=>$id]);
            }
            if($registerModel->is_sign==1){
                return $this->render('sign-done', [
                    'model' => $model,
                    'registerModel'=>$registerModel
                ]);
             
            }
        }
        }else{
            $registerModel=InterviewRegister::findOne(['activity_id'=>$id,'work_number'=>$work_number]);
            if(!empty($registerModel)){
                return $this->redirect(['view-register','id'=>$id]);
            }
        }
        
       $registerModel=new ActivityRegister();
       $registerModel->work_number=$user['work_number'];
       $registerModel->name=$user['real_name'];
       $registerModel->mobile=$user['mobile'];
       $registerModel->email=$user['email'];
       
       if($registerModel->load(yii::$app->request->post())){
           if($model->type==1&&!empty($_POST['signShop'])){
               $registerModel->sign_shop=$_POST['signShop'];
           }
           $registerModel->created_at=time();
          
           $registerModel->name=@$_POST['name'];
           $registerModel->work_number=@$_POST['work_number'];
           $registerModel->mobile=@$_POST['mobile'];
           $registerModel->answer=@$_POST['answer'];
           $registerModel->activity_id=$id;
           $registerModel->user_guid=$user_guid;
//            $registerModel->sign_qrcode=$qrName;
           if($registerModel->save()){
               yii::$app->getSession()->setFlash('success',"活动报名成功!");
               return $this->redirect(['view-register','id'=>$id]);
           }else{
               yii::$app->getSession()->setFlash('error',"提交失败,请重试!");
           }
       }
       
       $districtList=InterviewDistrict::find()->all();
       
        return $this->render('view', [
            'model' =>$model,
            'registerModel'=>$registerModel,
            'user'=>$user,
            'districtList'=>$districtList
        ]);
    }
    
    public function actionOuterRegister($id){
        $model=Activity::findOne($id);
        $user_guid=yii::$app->user->identity->user_guid;
        
        if($model->is_card_done==0){
            return $this->redirect($model->outer_link);
        }
        
        $card=Card::findOne(['user_guid'=>$user_guid]);
        
   
        
        if(!empty($card)){
            return $this->redirect($model->outer_link);
        }else{
            yii::$app->getSession()->set('outer_link', $model->outer_link);
            echo "<script>alert('您还没有创建名片,请先创建名片然后再报名.');<script>";
            return $this->redirect(['card/create']);
        }
    }
    
    public function actionInterviewRegister(){
        $user=yii::$app->user->identity;
        $name=@$_POST['name'];
        $mobile=@$_POST['mobile'];
        $work_number=@$_POST['work_number'];
        $district_code=@$_POST['district_code'];
        $district_name=@$_POST['district_name'];
        if(empty($name) || empty($mobile) || empty($work_number) || empty($district_code) || empty($district_name) ){
            yii::$app->getSession()->setFlash('error',"报名失败,请填写完整再提交!");
            return $this->redirect(yii::$app->request->referrer);
        }
        
        $interviewRegister=new InterviewRegister();
        $interviewRegister->user_guid=$user->user_guid;
        $interviewRegister->name=$name;
        $interviewRegister->mobile=$mobile;
        $interviewRegister->work_number=$work_number;
        $interviewRegister->district_code=$district_code;
        $interviewRegister->district_name=$district_name;
        $interviewRegister->year_month=date('Ym');
        $interviewRegister->activity_id=@$_POST['activity_id'];
        $interviewRegister->created_at=time();
        if($interviewRegister->save()){
            yii::$app->getSession()->setFlash('success',"报名成功,请等待资格审核!");
            return $this->redirect(['view-register','id'=>$interviewRegister->activity_id]);
        }else{
            yii::$app->getSession()->setFlash('error',"报名失败,请稍候重试!");
            return $this->redirect(yii::$app->request->referrer);
        }
        
        
    }
    
    public function actionViewRegister($id)
    {
       $work_number=yii::$app->user->identity->work_number;
       $activity=Activity::findOne($id);
       if($activity->type!=3){
           $registerModel=ActivityRegister::findOne(['activity_id'=>$id,'work_number'=>$work_number]);
       }else{
           $registerModel=InterviewRegister::findOne(['activity_id'=>$id,'work_number'=>$work_number]);
       }
       
        
        return $this->render('view-register', [
            'model' => $this->findModel($id),
            'registerModel'=>$registerModel
        ]);
    }
    
    public function actionSign($activity_id)
    {
        $user_guid=yii::$app->user->identity->user_guid;
        $user=User::findOne(['user_guid'=>$user_guid]);
        $work_number=$user->work_number;
        
        
        
        $registerModel=ActivityRegister::findOne(['activity_id'=>$activity_id,'work_number'=>$work_number]);
        if(empty($registerModel)){
            return $this->render("no-activity");
        }
        
        if($registerModel->is_sign==0){
            $registerModel->is_sign=1;
            $registerModel->sign_time=time();
            $registerModel->save();
        }
        
        return $this->render('sign-done', [
            'model' => $this->findModel($activity_id),
            'registerModel'=>$registerModel
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
            
            $photo=UploadedFile::getInstanceByName('photo');
            if(!empty($photo)){
                $basePath='../../upload/photo/';
                $path=date('Ymd').'/';
                if(!is_dir($basePath.$path)){
                    mkdir($basePath.$path);
                }
                $fileName=date('YmdHis').rand(0000, 9999).'.'.$photo->getExtension();
                $photo->saveAs($basePath.$path.$fileName);
                $model->path=$path;
                $model->photo=$fileName;
            
                $dst_dir=$basePath.$path.'thumb/';
                if(!is_dir($dst_dir)){
                    mkdir($dst_dir);
                }
                $thumb=new Image($basePath.$path.$fileName, $dst_dir.$fileName);
                $thumb->thumb(150,150);
                $thumb->out();
            }
            
            $model->content=$_POST['activity-content'];
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
        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->sign_end_time=strtotime($model->sign_end_time);
            
            $photo=UploadedFile::getInstanceByName('photo');
            if(!empty($photo)){
                $basePath='../../upload/photo/';
                $path=date('Ymd').'/';
                if(!is_dir($basePath.$path)){
                    mkdir($basePath.$path);
                }
                $fileName=date('YmdHis').rand(0000, 9999).'.'.$photo->getExtension();
                $photo->saveAs($basePath.$path.$fileName);
                $model->path=$path;
                $model->photo=$fileName;
            
                $dst_dir=$basePath.$path.'thumb/';
                if(!is_dir($dst_dir)){
                    mkdir($dst_dir);
                }
                $thumb=new Image($basePath.$path.$fileName, $dst_dir.$fileName);
                $thumb->thumb(150,150);
                $thumb->out();
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
