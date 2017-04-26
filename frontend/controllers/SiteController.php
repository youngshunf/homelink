<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\LoginForm;

use yii\filters\VerbFilter;


use frontend\models\AuthForm;
use common\models\WechatCallbackApi;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
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
                    [
                    'actions' => ['login','login-do','join-mvp','logout','index','mvp-lesson','error','wechat-api','test-get'],
                    'allow' => true,
                    'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
              /*   'actions' => [
                    'logout' => ['post'],
                ], */
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function beforeAction($action){
         
        if($action->id=='auth'){
          yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        }    
        return  parent::beforeAction($action);
    }

    public function actionIndex()   
    {
      return $this->render('index');
    }
    
    public function actionWechatApi(){
        $wechatObj = new WechatCallbackApi();
        if (!isset($_GET['echostr'])) {
         $wechatObj->responseMsg();
        }else{
          $wechatObj->valid();
        }
        
    }
    
 public function actionTestGet(){
     $data=[
         'name'=>@$_GET['name'],
         'district'=>@$_GET['district'],
         'shop'=>@$_GET['shop'],
         'businessCircle'=>@$_GET['businessCircle'],
         'building'=>@$_GET['building'],
     ];
     return json_encode($data);
 }
   public function actionParamError(){
       return $this->render('param-error');
   }
   
   public function actionError(){
       return $this->render('error');
   }
   
   
   public function actionNoAuth(){
       return $this->render('no-auth');
   }
   
   public function actionPermissionDeny(){
       return $this->render('permission-deny');
   }
    
    public function actionAuth(){
               
            if(yii::$app->user->identity->is_auth==1){
                return $this->render('auth-done');
            }
            
           $model=new AuthForm();
           if($model->load(yii::$app->request->post())){
               if($model->AuthUser()){
                   return $this->render('auth-success');
               }else{
                 
                   return $this->render('auth',['model'=>$model]);
               }
              
           }
             return $this->render('auth',['model'=>$model]);
    }
    
    public function actionStar(){
        $url="http://mvp.homelink.com.cn/mvpvote/wxMvpVote/toVoteDesc/".yii::$app->user->identity->work_number;
        return $this->redirect($url);
    }
    
    public function actionMvpLesson(){
        return  $this->render('mvp-lesson');
    }

    public function actionLogin()
    {
        $appid=yii::$app->params['appid'];
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http://mvp.homelink.com.cn/site/login-do&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
//         $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http://localhost/homelink/frontend/web/site/login-do&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        return $this->redirect($url);
    }
    
    public function  actionLoginDo(){
        if(!isset($_GET['code'])){
            return $this->redirect(['site/param-error']);
        }
        $code=$_GET['code'];
    
        $model=new LoginForm();
        if($model->Login($code)){
            return $this->goBack();
        }else{
            return $this->redirect(['site/login-fail']);
        }
   
    }
    
    public function actionLogout(){
        yii::$app->getUser()->logout();
        yii::$app->getSession()->setFlash('success','退出登录成功!');
        return $this->goHome();
    }
    
    public function actionJoinMvp(){
        return $this->render('join-mvp');
    }
    
    public function actionLoginFail(){
        return $this->render('login-fail');
    }
    
    public function actionRegister(){
        
    	
    }    
    
    public function actionSocial(){
        $openid=yii::$app->user->identity->openid;
        return $this->redirect("http://wx.lianjia.ourats.com/req/list/?type=social&openid=$openid");
    }
    
    public function actionCampus(){
        $openid=yii::$app->user->identity->openid;
        return $this->redirect("http://wx.lianjia.ourats.com/req/list/?type=campus&openid=$openid");
    }
    
    public function actionApply(){
        $openid=yii::$app->user->identity->openid;
        return $this->redirect("http://wx.lianjia.ourats.com/apply/?openid=$openid");
    }
     
   
    
}
