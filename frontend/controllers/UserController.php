<?php

namespace frontend\controllers;

use Yii;
use common\models\Wish;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\CommonUtil;
use common\models\Album;
use common\models\ImageUploader;
use yii\filters\AccessControl;
use common\models\Order;
use yii\data\ActiveDataProvider;
use common\models\AuctionBidRec;
use common\models\LotteryRec;
use common\models\LotteryGoods;
use common\models\Message;
use common\models\Resume;
use common\models\School;
use common\models\InterviewResult;
use common\models\Interview;
use common\models\ResumePhoto;
use common\models\TestLink;
use common\models\ActivityRegister;
use common\models\ActivityStep;
use common\models\TaskResult;
use common\models\AuthUser;
use common\models\ReportResult;

/**
 * WishController implements the CRUD actions for Wish model.
 */
class UserController extends Controller
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
              /*   'actions' => [
                    'delete' => ['post'],
                ], */
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
     * Lists all Wish models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user_guid=yii::$app->user->identity->user_guid;
        $model=User::findOne(['user_guid'=>$user_guid]);
        return $this->render('index',[
          'model'=>$model,        
      ]);
    }
    
    public function actionCreateResume()
    {
        
        $model=new Resume();
        $user=yii::$app->user->identity;
        $model->name=$user->name;
        $model->mobile=$user->mobile;
        $model->sex=$user->sex;
        
        if ($model->load(Yii::$app->request->post())  ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->graduation_time= strtotime($model->graduation_time);
            $model->school=@$_POST['school'];
            $model->schoolid=@$_POST['schoolid'];
            if(empty($model->schoolid)){
                yii::$app->getSession()->setFlash('error','请选择毕业院校!');
                return $this->redirect(yii::$app->request->referrer);
            }
            $model->created_at=time();
            if($model->save()){
                $photos=ImageUploader::uploadByNames('photo');
                if($photos){
                    foreach ($photos as $v){
                        $resumePhoto=new ResumePhoto();
                        $resumePhoto->resumeid=$model->id;
                        $resumePhoto->user_guid=$model->user_guid;
                        $resumePhoto->path=$v['path'];
                        $resumePhoto->photo=$v['photo'];
                        $resumePhoto->created_at=time();
                        $resumePhoto->save();
                    }
                }
                
                return $this->redirect(['view-resume','id'=>$model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','简历创建失败!');
            }
            
        }
        $schoolList=School::find()->orderBy('rank asc')->all();
        return $this->render('create-resume',[
            'model'=>$model,
            'schoolList'=>$schoolList
        ]);
    }
    
    public function actionUpdateResume($id)
    {
        
        $model=Resume::findOne($id);
        
        if ($model->load(Yii::$app->request->post())  ) {
            $model->graduation_time= strtotime($model->graduation_time);
            $model->school=@$_POST['school'];
            $model->schoolid=@$_POST['schoolid'];
            if(empty($model->schoolid)){
                yii::$app->getSession()->setFlash('error','请选择毕业院校!');
                return $this->redirect(yii::$app->request->referrer);
            }
            $model->updated_at=time();
            
            if($model->save()){
                $photos=ImageUploader::uploadByNames('photo');
                if($photos){
                foreach ($photos as $v){
                    $resumePhoto=new ResumePhoto();
                    $resumePhoto->resumeid=$model->id;
                    $resumePhoto->user_guid=$model->user_guid;
                    $resumePhoto->path=$v['path'];
                    $resumePhoto->photo=$v['photo'];
                    $resumePhoto->created_at=time();
                    $resumePhoto->save();
                }
                }
                return $this->redirect(['view-resume','id'=>$model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','简历更新失败!');
            }
            
        }
        $schoolList=School::find()->orderBy('rank asc')->all();
        return $this->render('update-resume',[
            'model'=>$model,
            'schoolList'=>$schoolList
        ]);
    }
    
    public function actionViewResume($id)
    {
        
        $model=Resume::findOne($id);
        return $this->render('view-resume',[
            'model'=>$model,
        ]);
    }
    
    public function actionViewUserResume($user_guid)
    {
        
        $model=Resume::findOne(['user_guid'=>$user_guid]);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','该用户还没有创建简历!');
            return $this->redirect(yii::$app->request->referrer);
        }
        return $this->redirect(['view-resume',
            'id'=>$model->id,
        ]);
    }
    
    protected function getTestlink($user_guid){
        $testlink=TestLink::find()->andWhere(['is_use'=>0])->all();
        $max=count($testlink);
        $index=rand(0,$max);
        $testlink[$index]->is_use=1;
        $testlink[$index]->user_guid=$user_guid;
        $testlink[$index]->save();
        return $testlink[$index]->link;
    }
    
    public function actionAssign($id)
    {
        $model = new Interview();
        
        if ($model->load(Yii::$app->request->post()) ) {
            $result=InterviewResult::findOne($id);
            if($result->status==0){
                $model->stage=1;
                $link=$this->getTestlink($result->user_guid);
                $result->test_link=$link;
            }else{
                $model->stage=2;
            }
            $result->status +=1;
            $result->save();
            $model->time=strtotime($model->time);
            $model->user_guid=$result->user_guid;
            $model->resultid=$result->id;
            $model->jobid=$result->jobid;
            $model->created_at=time();
            if( $model->save()){
                yii::$app->getSession()->setFlash('success','分配成功!');
                return $this->redirect(['interview-result', 'id' => $id]);
            }else{
                yii::$app->getSession()->setFlash('error','操作失败!');
            }
            
        }
        $user=User::find()->andWhere(['role_id'=>['2','4']])->all();
        return $this->render('assign', [
            'model' => $model,
            'user'=>$user
        ]);
        
    }

    
    
    public function actionMyReport(){
        
//         yii::$app->getSession()->setFlash('error','暂未开通,敬请期待!');
        $user=yii::$app->user->identity;
        $time=time();
        if($user->role_id==3 || $user->role_id==4){
            $downUser=AuthUser::findAll(['up_work_number'=>$user->work_number]);
            $numbers=[];
            foreach ($downUser as $v){
                $numbers[]=$v->work_number;
            }
            $dataProvider=new ActiveDataProvider([
                'query'=>ReportResult::find()->andWhere(['work_number'=>$numbers])->andWhere(" report_time <= $time")->orderBy("created_at desc"),
            ]);
        }else{
            $dataProvider=new ActiveDataProvider([
                'query'=>ReportResult::find()->andWhere(['work_number'=>$user->work_number])->andWhere(" report_time <= $time")->orderBy("created_at desc"),
            ]);
        }
       
        
        
        return $this->render('my-report',['dataProvider'=>$dataProvider]);       
    }
    
    public function  actionMyActivity(){
        $user=yii::$app->user->identity;
        $dataProvider=new ActiveDataProvider([
            'query'=>ActivityRegister::find()->andWhere(['user_guid'=>$user->user_guid])->orderBy("created_at desc"),
            'pagination'=>[
                'pagesize'=>20
            ]
        ]);
        return $this->render('my-activity',['dataProvider'=>$dataProvider]);
    }
    
    public function actionDownTask(){
        $user=yii::$app->user->identity;
        $downUser=AuthUser::findAll(['up_work_number'=>$user->work_number]);
        $work_numbers=[];
        foreach ($downUser as $v){
            $work_numbers[]=$v->work_number;
        }
        $dataProvider=new ActiveDataProvider([
            'query'=>TaskResult::find()->andWhere(['work_number'=>$work_numbers])->orderBy('created_at desc')
        ]);
        return $this->render('down-task',['dataProvider'=>$dataProvider]);
    }
    
    public function actionDownUser(){
        $user=yii::$app->user->identity;
        $downUser=AuthUser::findAll(['up_work_number'=>$user->work_number]);
        $work_numbers=[];
        foreach ($downUser as $v){
            $work_numbers[]=$v->work_number;
        }
        $dataProvider=new ActiveDataProvider([
            'query'=>User::find()->andWhere(['work_number'=>$work_numbers])->orderBy('created_at desc')
        ]);
        return $this->render('down-user',['dataProvider'=>$dataProvider]);
    }
    public function actionMyTask(){
        $user=yii::$app->user->identity;
        $dataProvider=new ActiveDataProvider([
            'query'=>TaskResult::find()->andWhere(['user_guid'=>$user->user_guid])->orderBy('created_at desc')
        ]);
        return $this->render('my-task',['dataProvider'=>$dataProvider]);
    }
    
    public function  actionActivityStep($id){
        $user=yii::$app->user->identity;
        $model=ActivityRegister::findOne($id);
        return $this->render('activity-step',['model'=>$model]);
    }
    
    public function  actionInterviewer(){
        $user_guid=yii::$app->user->identity->user_guid;
        $dataProvider=new ActiveDataProvider([
            'query'=>Interview::find()->andWhere(['interview_user'=>$user_guid])->orderBy("created_at desc"),
            'pagination'=>[
                'pagesize'=>20
            ]
        ]);
        return $this->render('interviewer',['dataProvider'=>$dataProvider]);
    }
    
    public function actionInterviewResult($id){
        $model=InterviewResult::findOne($id);
        $dataProvider=new ActiveDataProvider([
            'query'=>Interview::find()->andWhere(['resultid'=>$model->id])->orderBy("created_at desc"),
        ]);
        return $this->render('interview-result',[
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionInterviewComment($id){
        $model=Interview::findOne($id);
        $result=InterviewResult::findOne(['id'=>$model->resultid]);
        if ($model->load(Yii::$app->request->post() )  ) {
           
            if($model->status==1 &&$model->stage==1){
                $result->status=2;
            }elseif ($model->status==2 &&$model->stage==1){
                $result->status=98;
            }elseif ($model->status==1 &&$model->stage==2){
                $result->status=4;
            }elseif ($model->status==2 &&$model->stage==2){
                $result->status=99;
            }
            $result->save();
            if($model->save() ){
                yii::$app->getSession()->setFlash('success','提交成功!');
            }else{
                yii::$app->getSession()->setFlash('error','提交失败!');
            }
            
        }
        return $this->render('interview-comment',[
            'model'=>$model,
            'result'=>$result
        ]);
    }
    
    
    
    public function actionUpdateProfile(){
        $user_guid= yii::$app->user->identity->user_guid;
        $model=User::findOne(['user_guid'=>$user_guid]);
        if(!empty($model->birthday)){
            $model->birthday=CommonUtil::fomatDate($model->birthday);
        }
       
        if ($model->load(Yii::$app->request->post()) ) {
            $model->updated_at=time();
            if(!empty($model->birthday)){
                $model->birthday=strtotime($model->birthday);
            }
            if($model->save()){
                return $this->redirect(['profile','id'=>$model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','个人信息修改失败');
            }
               
        } 
            return $this->render('update-profile', [
                'model' => $model,
            ]);
        
    
    }
    
    
    public function actionMyProfile(){
        
        return $this->redirect(['profile','user_guid'=>yii::$app->user->identity->user_guid]);
    }
    
    
    public  function actionProfile($id){
        $model=User::findOne($id);
        return $this->render('profile',['model'=>$model]);
    }
    
    public function actionAddUserProfile(){
        $user_guid= yii::$app->user->identity->user_guid;
       $model=User::findOne(['user_guid'=>$user_guid]);
        $model->setScenario('update');
        if ($model->load(Yii::$app->request->post()) ) {
            $model->is_profile_done=1;
            $model->age=date('Y')-date('Y',strtotime($model->birthday));
            $model->created_at=time();
            if($model->save())
               return $this->redirect(['create']);
        } else {
            return $this->render('add-user-profile', [
                'model' => $model,
            ]);
        }
        
    }
    
    public function actionUploadImg(){
        $photo=ImageUploader::uploadByName('img');
        $user_guid=yii::$app->user->identity->user_guid;
        if($photo){
            $path=$photo['path'];
            $photo=$photo['photo'];
            $updated_at=time();
            User::updateAll(['path'=>$path,'photo'=>$photo,'updated_at'=>$updated_at],['user_guid'=>$user_guid]);
            
        }
        return $this->redirect(yii::$app->request->referrer);
    }


}
