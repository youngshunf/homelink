<?php
namespace console\controllers;
use yii\console\Controller;
use common\models\CommonUtil;
use common\models\User;
use common\models\BalanceEventHandler;
use common\models\FastAward;
use common\models\FastEventHandler;
use yii;
use common\models\WeekCloseEventHandler;
use common\models\WeekTotalHandler;
class WeekCheckController extends Controller{
    
    public function actionIndex(){
      echo  CommonUtil::LogMsg('每周运行');
    }
    //大对碰处理
    public function actionBigBalance() {
        // 一次提取 10 个用户并一个一个地遍历处理，减少内存占用
        $i=0;
       
        $balanceAward=new BalanceEventHandler();
        $this->on(CommonUtil::BALANCE_AWARD_EVENT,[$balanceAward,BalanceEventHandler::BALANCE_AWARD_HANDLER]);
        //过了犹豫期才计算大对碰
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each() as $user) {
            $i++;
            echo CommonUtil::LogMsg("开始计算第".$i."个用户",$user['real_name']);                        
                        $balanceAward->user_guid=$user['user_guid'];                    
                        $this->trigger(CommonUtil::BALANCE_AWARD_EVENT,$balanceAward);                            
            }      

            $this->off(CommonUtil::BALANCE_AWARD_EVENT,[$balanceAward,BalanceEventHandler::BALANCE_AWARD_HANDLER]);
        echo CommonUtil::LogMsg("大对碰计算完毕,有".$i."个用户进行了大对碰");
          
    }
    
    //快速奖 在注册后12周内，完成左45右45，公司再赠送5000美金
    public function actionFastAward(){
        $i=0;
        $fastAwardHandler=new FastEventHandler();
        $this->on(CommonUtil::FAST_AWARD, [$fastAwardHandler,FastEventHandler::FAST_AWARD_HANDLER]);
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each(10) as $user) {
            $startTime=$user['insert_time'];
            $twelveWeek=3600*24*7*12;
            $period=time()-$startTime;
            if($period>=$twelveWeek){
                $fastAward=FastAward::findOne(['user_guid'=>$user['user_guid']]);
                //超过12周未进行快速奖计算则进行，已进行过计算不再重复计算
                if(!isset($fastAward)){                  
                    $fastAwardHandler->user_guid=$user['user_guid'];                 
                    $this->trigger(CommonUtil::FAST_AWARD,$fastAwardHandler);
                    $i++;
                }
            }
                 
        }
        //计算完毕解绑事件
        $this->off(CommonUtil::FAST_AWARD, [$fastAwardHandler,FastEventHandler::FAST_AWARD_HANDLER]);
        echo CommonUtil::LogMsg("快速奖计算完毕,有".$i."个用户获得了快速奖");
    }
    //周结算
    public function actionWeekClose(){
       /*  $lastWeek=CommonUtil::getLastWeek();
          $lastWeekStart=strtotime($lastWeek[0]);
          $lastWeekEnd=strtotime($lastWeek[1]); 
          echo $lastWeek[0].$lastWeek[1];die; */
        $year=date('Y',time());
        $week=date('W',time())-1;
        $year_month=date('Ym',time());
       $nowWeek=CommonUtil::getWeekTime();
        $lastWeekStart=strtotime($nowWeek['last_week']['last_start']);
        $lastWeekEnd=strtotime($nowWeek['last_week']['last_end']);
       // echo $nowWeek['last_week']['last_start'].$nowWeek['last_week']['last_end'];die;
          $weekCloseHandler=new WeekCloseEventHandler();
          $this->on(WeekCloseEventHandler::WEEK_CLOSE_HANDLER, [$weekCloseHandler,WeekCloseEventHandler::WEEK_CLOSE_HANDLER]);
            
          $i=0;
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each(10) as $user) {
      
            $user_guid=$user->user_guid;
            //会员周结算       
                $weekCloseHandler->year=$year;
                $weekCloseHandler->week=$week;
               $weekCloseHandler->user_guid=$user_guid;
               $weekCloseHandler->real_name=$user->real_name;
               $weekCloseHandler->start_time=$lastWeekStart;
               $weekCloseHandler->end_time=$lastWeekEnd;
               $weekCloseHandler->week_top_money=CommonUtil::WEEK_TOP_MONEY_NORMAL;
               $this->trigger(WeekCloseEventHandler::WEEK_CLOSE_HANDLER,$weekCloseHandler);       
        }
        $this->off(WeekCloseEventHandler::WEEK_CLOSE_HANDLER, [$weekCloseHandler,WeekCloseEventHandler::WEEK_CLOSE_HANDLER]);
              
 
    }
    //周统计
    public function actionWeekTotal(){
        $year=date('Y',time());
        $week=date('W',time())-1;
        $year_month=date('Ym',time());
        $nowWeek=CommonUtil::getWeekTime();
        $lastWeekStart=strtotime($nowWeek['last_week']['last_start']);
        $lastWeekEnd=strtotime($nowWeek['last_week']['last_end']);
        //周统计
        $weekTotal=new WeekTotalHandler();
        $weekTotal->year=$year;
        $weekTotal->week=$week;
        $weekTotal->year_moth=$year_month;
        $weekTotal->start_time=$lastWeekStart;
        $weekTotal->end_time=$lastWeekEnd;
        $this->on(WeekTotalHandler::WEEK_TOTAL_HANDLER, [$weekTotal,WeekTotalHandler::WEEK_TOTAL_HANDLER]);
        $this->trigger(WeekTotalHandler::WEEK_TOTAL_HANDLER,$weekTotal);
      //  $this->off(WeekTotalHandler::WEEK_TOTAL_HANDLER, [$weekTotal,WeekTotalHandler::WEEK_TOTAL_HANDLER]);
        
    }
        
    
    
}