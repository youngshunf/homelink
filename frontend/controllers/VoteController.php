<?php

namespace frontend\controllers;

use Yii;
use common\models\Vote;
use common\models\SearchVote;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use common\models\VoteItem;
use yii\filters\AccessControl;
use common\models\Image;
use common\models\VoteResult;
use common\models\CommonUtil;

/**
 * VoteController implements the CRUD actions for Vote model.
 */
class VoteController extends Controller
{
    public $enableCsrfValidation = false;
/*   public function behaviors()
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
    } */
    
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
     * Lists all Vote models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchVote();
        $searchModel->status=SearchVote::GOING;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vote model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user_guid=yii::$app->user->identity->user_guid;
        $voteItem=VoteItem::findAll(['vote_id'=>$id]);
        $voteResult=VoteResult::findOne(['vote_id'=>$id,'user_guid'=>$user_guid]);
        if(!empty($voteResult)){
            $isVote=1;
        }else{
            $isVote=0;
        }
        return $this->renderPartial('vote', [
            'model' => $this->findModel($id),
            'voteItem'=>$voteItem,
            'isVote'=>$isVote
        ]);
    }
    
    public function actionVote()
    {
      $optArr=$_POST['optArr'];
      $vote_id=$_POST['voteid'];
      foreach ($optArr as $k=>$v){
          $voteItem=VoteItem::findOne(['vote_item_id'=>$v]);
          $voteItem->vote_number+=1;
          $voteItem->updated_at=time();
          if($voteItem->save()){
           
          }
      }
      $vote=Vote::findOne(['vote_id'=>$vote_id]);
      $vote->vote_number+=1;
      $vote->updated_at=time();
      $vote_id=$vote->vote_id;
      $vote->save();
      
      $voteResult=new VoteResult();
      $voteResult->vote_id=$vote_id;
      $voteResult->vote_item_id=implode($optArr, ',');
      if(!yii::$app->user->isGuest){
          $voteResult->user_guid=yii::$app->user->identity->user_guid;
      }
      $voteResult->ip=CommonUtil::getClientIp();
      $voteResult->created_at=time();
      $voteResult->save();
      
      return $this->redirect(['view','id'=>$vote_id]);
      
    }

    /**
     * Creates a new Vote model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vote();
        if ($model->load(Yii::$app->request->post()) ) {
            $photo=UploadedFile::getInstance($model, 'photo');
            if(!empty($photo)){                        
            $basePath='../../upload/photo/';
            $path=date('Ymd').'/';
            if(!is_dir($basePath.$path)){
                mkdir($basePath.$path);
             }
            $fileName=date('YmdHis').rand(0000, 9999).'.'.$photo->extension;
            $photo->saveAs($basePath.$path.$fileName);
            $model->path=$path;
            $model->photo=$fileName;
            $thumbDir=$basePath.$path.'thumb/';
            if(!is_dir($thumbDir)){
                mkdir($thumbDir);
            }
            $thumb=new Image($basePath.$path.$fileName, $thumbDir.$fileName);
            $thumb->thumb(120,120);
            $thumb->out();
             }
            $model->created_at=time();
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->vote_id]);
            }else{
                yii::$app->getSession()->setFlash('error','提交失败,服务器错误!');
                 return $this->render('create', [
                'model' => $model,
              ]);
            }
          
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Vote model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {        
            $model->updated_at=time();
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->vote_id]);
            }else{
                yii::$app->getSession()->setFlash('error','提交失败,服务器错误!');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Vote model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteItem($id)
    {
        $item=VoteItem::findOne($id);
        $vote_id=$item->vote_id;
        $item->delete();
    
        return $this->redirect(['view','id'=>$vote_id]);
    }

    /**
     * Finds the Vote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
