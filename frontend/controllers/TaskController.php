<?php

namespace frontend\Controllers;

use Yii;
use common\models\Task;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\TaskResult;
use yii\filters\AccessControl;
use common\models\User;
use common\models\GrowthRec;
use common\models\WeChatTemplate;
use common\models\CommonUtil;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role_id=yii::$app->user->identity->role_id;
//         if($role_id==4||$role_id==3){
//             return $this->redirect('task-result-list');
//         }else
            
       if ($role_id==1 || $role_id==4||$role_id==3){
            return $this->redirect(['task-list']);
        }else{
            return $this->redirect(['site/permission-deny']);
        }
    }

  public function actionTaskResultList(){
      $user=yii::$app->user->identity;
      $dataProvider=new ActiveDataProvider([
          'query'=>TaskResult::find()->andWhere(['business_district'=>$user->business_district])->orderBy('created_at desc')
      ]);
      
      return $this->render('task-result-list',[
          'dataProvider'=>$dataProvider
      ]);
  }
  
  public function actionMvpGrow(){
      $user=yii::$app->user->identity;
      if($user->role_id==3||$user->role_id==4){
          $dataProvider=new ActiveDataProvider([
              'query'=>User::find()->andWhere(['business_district'=>$user->business_district,'is_auth'=>1])->orderBy('created_at desc')
          ]);
          return $this->render('grow-list',[
              'dataProvider'=>$dataProvider
          ]);
      }elseif ($user->role_id==1){
        return $this->redirect(['grow-view','work_number'=>$user->work_number]);
      }else{
          return $this->redirect(['site/permission-deny']);
      }
  }
  
  public function actionGrowView($work_number){
      $growRec=GrowthRec::find()->andWhere(['work_number'=>$work_number])->orderBy('item_time desc')->all();
      $taskResult=TaskResult::find()->andWhere(['work_number'=>$work_number,'is_comment'=>1])->all();
        
      return $this->render('grow-view',[
          'work_number'=>$work_number,
          'growRec'=>$growRec,
          'taskResult'=>$taskResult
      ]);
  }
  
  /**
   * 任务列表
   * @return Ambigous <string, string>
   */
  public function actionTaskList(){
      $dataProvider=new ActiveDataProvider([
          'query'=>Task::find()->orderBy('created_at desc')
      ]);
  
      return $this->render('task-list',[
          'dataProvider'=>$dataProvider
      ]);
  }
  
  public function actionSearchMvp(){
      $user=yii::$app->user->identity;
      if($user->role_id==3||$user->role_id==4){
     $query=User::find()->andWhere(['business_district'=>$user->business_district,'is_auth'=>1])->orderBy('created_at desc');
         
               if (!empty($_POST['name'])){
                   $query->andFilterWhere(['like', 'real_name',$_POST['name']]);
               }
               if(!empty($_POST['work_number'])){
                   $query->andWhere(['work_number'=>$_POST['work_number']]);
               }
               $dataProvider=new ActiveDataProvider([
                   'query'=>$query
               ]);
          return $this->render('grow-list',[
              'dataProvider'=>$dataProvider
          ]);
      }else{
          return $this->redirect(['site/permission-deny']);
      }
  }
  
  /**
   * 任务详情
   * @param unknown $id
   * @return Ambigous <string, string>
   */
  public function actionView($id){
      $model=Task::findOne($id);
      return $this->render('view',['model'=>$model]);
  }
  
  /**
   * 任务评分
   * @param unknown $id
   * @return \yii\web\Response|Ambigous <string, string>
   */
  public function actionCommentTask($id){
        $user=yii::$app->user->identity;
      $model=TaskResult::findOne($id);
      if($user->role_id!=3&&$user->role_id!=4){
          yii::$app->getSession()->setFlash('error','对不起,您没有权限评分!');
          return $this->redirect(yii::$app->request->referrer);
      }
      
      if($model->is_comment==1&&$model->comment_user!=$user->user_guid){
          yii::$app->getSession()->setFlash('error','对不起,此任务已经被别人评过分了,您不能再评分!');
          return $this->redirect(yii::$app->request->referrer);
      }
      
      $model->setScenario('comment');
      if($model->load(yii::$app->request->post())){
          $model->updated_at=time();
          $model->comment_user=$user->user_guid;
          $model->is_comment=1;
          $task=Task::findOne(['id'=>$model->task_id]);
          if($model->comment_result==0){//任务未完成不能获得分数
              $model->score=0;
          }elseif($model->comment_result==1){//任务完成获得任务分数
              $model->score=$task->score;
          }
          $model->status=2;
          if($model->save()){
              if($model->comment_result==1){
                  $growRec=GrowthRec::findOne(['user_guid'=>$model->user_guid,'task_id'=>$model->id]);
                  if(empty($growRec)){
                      $growRec=new GrowthRec();
                      $growRec->created_at=time();
                  }
                  $growRec->task_id=$model->id;
                  $growRec->user_guid=$model->user_guid;
                  $growRec->work_number=$model->work_number;
                  $growRec->items='完成任务 : '.$model->task_name;
                  $growRec->item_time=date('Y-m-d');
                  $growRec->score=$model->score;
                  $growRec->save();
              }
              yii::$app->getSession()->setFlash('success','评价成功!');
              return $this->redirect(['view','id'=>$model->task_id]);
          }
      }else{
          return $this->render('comment-task',['model'=>$model]);
      }
      
  }
  
  public function actionTaskDone($id){
      if(yii::$app->user->identity->role_id!=1){
          yii::$app->getSession()->setFlash('error','对不起,您没有权限进行此操作!');
          return $this->redirect(yii::$app->request->referrer);
      }
    /*   $task=Task::findOne($id);
      $year_month=date('Ym');
      $user=yii::$app->user->identity;
      */
      $userTaskResult=TaskResult::findOne($id);
      if(empty($userTaskResult)){
          yii::$app->getSession()->setFlash('error','对不起，任务不存在!');
          return $this->redirect(yii::$app->request->referrer);
      } 
      
      $userTaskResult->status=1;
      if($userTaskResult->save()){
          yii::$app->getSession()->setFlash('success','操作成功!');
          }else{
              yii::$app->getSession()->setFlash('error','操作失败!');
          }
          
          return $this->redirect(yii::$app->request->referrer);
  }
  
  public function actionGetTask(){
      $id=$_POST['task_id'];
      if(yii::$app->user->identity->role_id!=1){
          yii::$app->getSession()->setFlash('error','对不起,您没有权限领取任务!');
          return $this->redirect(yii::$app->request->referrer);
      }
      $task=Task::findOne($id);
      $year_month=date('Ym');
      $user=yii::$app->user->identity;
  /*     $userTaskResult=TaskResult::findOne(['task_id'=>$id,'user_guid'=>$user->user_guid,'year_month'=>$year_month]);
      if(!empty($userTaskResult)){
          yii::$app->getSession()->setFlash('error','您本月已经领取过此任务,请不要重复领取!');
          return $this->redirect(yii::$app->request->referrer);
      } */
      
      $taskResult=new TaskResult();
      $taskResult->task_id=$task->id;
      $taskResult->task_name=$task->name;
      $taskResult->user_guid=$user->user_guid;
      $taskResult->business_district=$user->business_district;
      $taskResult->work_number=$user->work_number;
      $taskResult->year_month=$year_month;
      $taskResult->start_time=strtotime($_POST['start_time']);
      $taskResult->end_time=strtotime($_POST['end_time']);
      $taskResult->created_at=time();
      if($taskResult->save()){
          $task->count_exec+=1;
          $task->save();
          
          $upUser=User::findOne(['business_district'=>$user->business_district,'role_id'=>3]);
          if(!empty($upUser)){
          //发送模板消息
          $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
          $data=array();
          $data['first']=[
              'value'=>"MVP【".$user->real_name."】领取了任务",
              "color"=>"#173177"
          ];
          $data['keyword1']=[
            'value'=>'任务名称:'.$task->name,
              "color"=>"#173177"
          ];
          $data['keyword2']=[
              'value'=>'领取时间:'.CommonUtil::fomatTime($taskResult->created_at),
              "color"=>"#173177"
          ];
          $data['remark']=[
              'value'=>"立即点击此处进行评价吧",
              "color"=>"#173177"
          ];
          
          $finalData=[
              "touser"=>$upUser->openid,
              "template_id"=>'8BaMmH1MhYZadc4IEOB-xVu9JCmKgjHqWztGQJveUrs',
              "url"=>yii::$app->urlManager->createAbsoluteUrl(['task/view','id'=>$task->id]),
              "topcolor"=>"#FF0000",
              "data"=>$data
          ];
           $res=$sendModel->send_template_message($finalData);
          }
          
          yii::$app->getSession()->setFlash('success','任务领取成功!');
      }else{
          yii::$app->getSession()->setFlash('error','任务领取失败!');
      }
      return $this->redirect(yii::$app->request->referrer);
  }

}
