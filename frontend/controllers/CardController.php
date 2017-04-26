<?php

namespace frontend\controllers;

use Yii;
use common\models\Card;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use dosamigos\qrcode\QrCode;
use dosamigos\qrcode\lib\Enum;
use common\models\User;
use common\models\CardComment;
use common\models\CommonUtil;
use yii\filters\AccessControl;
use yii\db\Exception;
use common\models\ImageUploader;
use common\models\SearchAuthUser;
use frontend\models\CardSearch;

/**
 * CardController implements the CRUD actions for Card model.
 */
class CardController extends Controller
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
            [
            'class' => 'yii\filters\PageCache',
            'only' => ['view'],
            'variations' => [
                @$_GET['id'],
            ],
            'dependency' => [
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT updated_at FROM card where card_id='.@$_GET['id'],
            ],
            ],
            ];
    }
    
    public function beforeAction($action){
         
        yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        if($action->id=='comment'||$action->id=='view'){
            return  parent::beforeAction($action);
        }
            if(!yii::$app->user->isGuest){
                if(yii::$app->user->identity->is_auth==0){
                    return $this->redirect(['site/no-auth']);
                }
            }
        
        
        return  parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $searchModel = new SearchAuthUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $cardSearch=new CardSearch();

        return $this->render('index', [
            'cardSearch' => $cardSearch,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCardSearch()
    {
       $cardSearch=new CardSearch();
       $result=[];
       if($cardSearch->load(yii::$app->request->get())){
           $result=$cardSearch->getResult();
           $result=json_decode($result,true);
           $result=$result['data'];
       }
        return $this->render('card-search', [
            'cardSearch' => $cardSearch,
            'result' => $result,
        ]);
    }
    /**
     * Displays a single Card model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
        
        //二维码生成
        $qrPath='../../upload/photo/qrcode/';
        if(!is_dir($qrPath)){
            mkdir($qrPath);
        }
        $qrFile=$qrPath.$model->user_guid.'.png';
        if(!file_exists($qrFile)){
            QrCode::png(yii::$app->urlManager->createAbsoluteUrl('card/view')."?id=$model->card_id",$qrFile,Enum::QR_ECLEVEL_H,7);
        }
        $score=CardComment::find()->andWhere(['card_id'=>$model->card_id])->average('score');
        $description=mb_substr($model->sign, 0,20);
        $weixinImg=yii::getAlias('@avatar').'/'.$model->path.'thumb/'.$model->photo;
        if(empty($model->template)){
            return $this->render('card-view', [
                'model' => $this->findModel($id),
                'description'=>$description,
                'weixinImg'=>$weixinImg,
                'score'=>$score
            ]);
        }
        return $this->render($model->template.'-view', [
            'model' => $this->findModel($id),
            'description'=>$description,
            'weixinImg'=>$weixinImg,
            'score'=>$score
        ]);
    }

    /**
     * Creates a new Card model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //登录检查
     if(yii::$app->user->identity->is_auth==0){
         return $this->redirect(['site/no-auth']);
     }
     $user_guid=yii::$app->user->identity->user_guid;
     $user=User::findOne(['user_guid'=>$user_guid]);
    $card=Card::findOne(['user_guid'=>$user_guid]);
     if(!empty($card)){
         return $this->redirect(['view', 'id' => $card->card_id]);
     } 
        
        $model = new Card();
        $model->name=$user['real_name'];
        if ($model->load(Yii::$app->request->post())  ) {
           $model->user_guid=$user['user_guid'];
            $imgData=$_POST['imgData'];
            $imgLength=$_POST['imgLen'];
            $photo=ImageUploader::uploadImageByBase64($imgData, $imgLength);
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            
            $model->template=empty($_POST['template'])?'card':$_POST['template'];          
            $model->created_at=time();
            if($model->save()){
                $userArr=User::findOne(['user_guid'=>$user['user_guid']]);
                if(!empty($userArr)){
                    $userArr->real_name=$model->name;
                    $userArr->mobile=$model->mobile;
                    $userArr->email=$model->email;
                    $userArr->district=$model->district;
                    $userArr->address=$model->address;
                    $userArr->shop=$model->shop;
                    $userArr->business_circle=$model->business_circle;
                    $userArr->building=$model->building;
                    $userArr->path=$model->path;
                    $userArr->photo=$model->photo;
                    $userArr->sign=$model->sign;
                    $userArr->updated_at=time();
                    $userArr->save();
                }
                $outerLink=yii::$app->getSession()->get('outer_link');
                if(!empty($outerLink)){
                    echo "<script>alert('名片创建成功,即将跳转到报名页面.');<script>";
                    return $this->redirect($outerLink);
                }
                return $this->redirect(['view', 'id' => $model->card_id]);
            }     
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Card model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())  ) {
            if(!empty($_POST['imgData'])&&!empty($_POST['imgLen'])){
                   $imgData=$_POST['imgData'];
                    $imgLength=$_POST['imgLen'];
                    $photo=ImageUploader::uploadImageByBase64($imgData, $imgLength);
                    if($photo){
                        $model->path=$photo['path'];
                        $model->photo=$photo['photo'];
                    } 
            }          
            if(!empty($_POST['template'])){
                $model->template=$_POST['template'];
            }
     
            $model->updated_at=time();
            if( $model->save()){
                
               $user_guid=yii::$app->user->identity->user_guid;
                $userArr=User::findOne(['user_guid'=>$user_guid]);
                if(!empty($userArr)){
                    $userArr->real_name=$model->name;
                    $userArr->mobile=$model->mobile;
                    $userArr->email=$model->email;
                    $userArr->district=$model->district;
                    $userArr->address=$model->address;
                    $userArr->shop=$model->shop;
                    $userArr->business_circle=$model->business_circle;
                    $userArr->building=$model->building;
                    $userArr->path=$model->path;
                    $userArr->photo=$model->photo;
                    $userArr->sign=$model->sign;
                    $userArr->updated_at=time();
                    $userArr->save();
                }
                return $this->redirect(['view', 'id' => $model->card_id]);
            }                  
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionComment($id){
        $card=$this->findModel($id);
        
        $model=new CardComment();
        $model->card_id=$id;
        if($model->load(yii::$app->request->post())){
          
            if(!empty(yii::$app->user->isGuest)){
                $model->user_guid=yii::$app->user->identity->user_guid;
            }
            $trans=yii::$app->db->beginTransaction();
            try{
            $model->ip=CommonUtil::getClientIp();
            $model->score=$_POST['score'];
            $model->created_at=time();
            if(!$model->save()){
              throw new Exception();
            }   
            
            $card->score=CardComment::find()->andWhere(['card_id'=>$id])->average('score');
            $card->updated_at=time();
            if(!$card->save()){
                throw new Exception();
            }
            
            $trans->commit();
            }catch (Exception $e){
                $trans->rollBack();
                yii::$app->getSession()->setFlash('error','评论失败,请稍后重试!');
            }
            
            
            yii::$app->getSession()->setFlash('success','评论成功,感谢您的参与!');
            return $this->redirect(['view', 'id' => $model->card_id]);
        }
        
        return $this->render('comment',[
            'model'=>$model,
            'card'=>$card
        ]);
    }

    /**
     * Deletes an existing Card model.
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
     * Finds the Card model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Card the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Card::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
