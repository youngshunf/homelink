<?php
namespace console\controllers;
use yii\console\Controller;
use common\models\CommonUtil;
use common\models\User;
use common\models\LittleBalanceEventHandler;
use common\models\WheelEventHandler;
use common\models\CarHouseEventHandler;
use common\models\MonthTotalHandler;
use common\models\MonthCloseEventHandler;
use common\models\AwardCommonUtil;


class MonthCheckController extends Controller{
    
    public function actionIndex(){
      echo  CommonUtil::LogMsg('每月运行');
    }
    //小对碰计算
    public function actionLittleBalance() {
        // 一次提取 10 个用户并一个一个地遍历处理，减少内存占用
        $i=0;
        $littleBalance= new LittleBalanceEventHandler();
        $this->on(CommonUtil::LITTLE_BALANCE_AWARD,[$littleBalance,LittleBalanceEventHandler::LITTLE_BANLANCE_AWARD_HANDLER]);
        //过了犹豫期才计算小对碰
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each(10) as $user) {
          
            if($user['rank']>=2){             
                $littleBalance->user_guid=$user['user_guid'];              
                $this->trigger(CommonUtil::LITTLE_BALANCE_AWARD,$littleBalance);
                $i++;
            }
        }        
        $this->off(CommonUtil::LITTLE_BALANCE_AWARD,[$littleBalance,LittleBalanceEventHandler::LITTLE_BANLANCE_AWARD_HANDLER]);
        echo CommonUtil::LogMsg("小对碰计算完成,有".$i."个用户进行了小对碰奖励计算");
          
    }
    //轮子奖计算
    public function actionWheelAward(){
        $i=0;
        $wheelAward=new WheelEventHandler();
        $this->on(CommonUtil::WHEEL_AWARD, [$wheelAward,WheelEventHandler::WHEEL_AWARD_HANDLER]);
        // 一次提取 10 个用户并一个一个地遍历处理，减少内存占用       
        //过了犹豫期才计算轮子奖
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each(10) as $user) {        
            $wheelAward->user_guid=$user['user_guid'];            
            $this->trigger(CommonUtil::WHEEL_AWARD,$wheelAward);
            $i++;           
        }
        
        $this->off(CommonUtil::WHEEL_AWARD, [$wheelAward,WheelEventHandler::WHEEL_AWARD_HANDLER]);
        echo CommonUtil::LogMsg("轮子奖计算完成,有".$i."个用户进行了轮子奖计算");
    }
    
    //车奖房奖计算
    public function actionCarHouse(){
        $i=0;
        $carHouse=new CarHouseEventHandler();
        $this->on(CommonUtil::CAR_HOUSE_AWARD, [$carHouse,CarHouseEventHandler::CAR_HOUSE_AWARD_HANDLER]);
        // 一次提取 10 个用户并一个一个地遍历处理，减少内存占用
        //过了犹豫期才计算车奖、房奖
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each(10) as $user) {
            
            if($user['rank']>=CommonUtil::RANK_RMD){         
           $carHouse->user_guid=$user['user_guid'];           
            $this->trigger(CommonUtil::CAR_HOUSE_AWARD,$carHouse);
            $i++;
        }
        
    }
    
    $this->off(CommonUtil::CAR_HOUSE_AWARD, [$carHouse,CarHouseEventHandler::CAR_HOUSE_AWARD_HANDLER]);
    echo CommonUtil::LogMsg("车奖、房奖计算完成,有".$i."个用户进行了车奖、房奖计算");
    }
    
    /**
     * @author youngshunf
     * 奖金月结算
     */
    public function actionMonthClose(){
        $monthCloseEvenHandler=new MonthCloseEventHandler();
        $monthCloseEvenHandler->start_time=strtotime(CommonUtil::getMonthDay(-1)[0]);
        $monthCloseEvenHandler->end_time=strtotime(CommonUtil::getMonthDay(-1)[1]);
        $monthCloseEvenHandler->month_top_money=CommonUtil::MONTH_TOP_MONEY;
        $this->on(MonthCloseEventHandler::MONTH_CLOSE_HANDLER, [$monthCloseEvenHandler,MonthCloseEventHandler::MONTH_CLOSE_HANDLER]);
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NORMAL])->each(10) as $user){
            $monthCloseEvenHandler->user_guid=$user->user_guid;
            $this->trigger(MonthCloseEventHandler::MONTH_CLOSE_HANDLER,$monthCloseEvenHandler);
        }
        $this->off(MonthCloseEventHandler::MONTH_CLOSE_HANDLER);
        
    }
    
    
    //月统计
    public function actionMonthTotal(){
        $year_month=date('Ym',time());
        $start_time=CommonUtil::getMonthDay(-1)[0];
        $end_time=CommonUtil::getMonthDay(-1)[1];
       // echo $start_time.$end_time;die;
        $monthTotal=new MonthTotalHandler();
        $monthTotal->year_month=$year_month;
        $monthTotal->start_time=$start_time;
        $monthTotal->end_time=$end_time;
        $this->on(MonthTotalHandler::MONTH_HANDLER, [$monthTotal,MonthTotalHandler::MONTH_HANDLER]);
        $this->trigger(MonthTotalHandler::MONTH_HANDLER,$monthTotal);
         $this->off(MonthTotalHandler::MONTH_HANDLER, [$monthTotal,MonthTotalHandler::MONTH_HANDLER]);
    }
    
    
}