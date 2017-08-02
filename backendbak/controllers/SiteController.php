<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\LoginForm;
use common\models\RegisterForm;
use yii\filters\VerbFilter;
use common\models\MonthFeeCalEventHandler;
use common\models\MonthCloseEventHandler;
use common\models\MonthFee;
use common\models\CommonUtil;
use common\models\UserRelation;
use common\models\MembersOrder;
use common\models\Order;
use common\models\AwardCommonUtil;
use common\models\AwardPoints;
use common\models\WeChatTools;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
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

    public function actionIndex()
    {
//      $url="http://eagleeye.yisier.com/ApiProvider!cxMsResult.svr";
//    // $url="http://localhost/homelink/backend/web/site/test";
//      $secPwd='Y@c1Ai&Qx8';//Y%2540c1Ai%2526Qx8
//      $idNo='孙嘉辰';
//      $md5Auth=md5($secPwd.date('Y-m-d').$idNo);
   
//      $data=[
//          'secPwd'=>$secPwd,
//          'idNo'=>$idNo,
//          'md5Auth'=>$md5Auth
//      ];
//      print_r($data);
//      $result=$this->post($url,$data);
//     // $result=json_decode($result,true);
//     // echo $md5AuthStr; 
//    //  print_r($data);
//     $result=json_decode($result,true);
//     $i=0;
//     $content='';
//     if($result['code']=='1001'){
//         foreach ($result['results'] as $v){
//             $content .='
// 第'.($i++).'位:
// 姓名:'.@$v['sName'].';
// 身份证号:'.@$v['idNo'].';
// 电话:'.@$v['srPhone'].';
// 推荐人系统号:'.@$v['reeBrokerId'].';
// 推荐人:'.@$v['reeName'].';
// 面试时间:'.@$v['msDate'].';
// 面试结果:'.@$v['passStat'];
//         }
//     } 
//     echo $content;
//      print_r($result);die;
       $user=yii::$app->user->identity;
        if($user->role_id!=99){
            return $this->redirect('interview-supervisor');
        }
        return $this->render('index');
    }
    
    public function http_post($url,$data) {
      //  $url="http://www.3meima.com:8080/searchExcellent.do";
        $ch = curl_init ();
        $this_header = array(
            "content-type: application/x-www-form-urlencoded;
            charset=UTF-8"
        );
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1);
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return $return ;
    }
    
    
    public  function post($url, $data){//file_get_content
        $postdata = http_build_query(
            $data
        );
        $opts = array('http' =>
 
                      array(
 
                          'method'  => 'POST',
 
                          'header'  => 'Content-type: application/x-www-form-urlencoded',
 
                          'content' => $postdata
                      )
 
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
 
        return $result;
 
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionRegister(){
    	$model = new RegisterForm();
    	if ($model->load(Yii::$app->request->post()) && $model->register()) {
    		return $this->goBack();
    	} else {
    		return $this->render('register', [
    				'model' => $model,
    				]);
    	}
    }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->goHome();
    }
}
