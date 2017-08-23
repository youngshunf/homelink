<?php
namespace console\controllers;
use yii\console\Controller;
use common\models\CommonUtil;
use common\models\User;
use common\models\SalesAward;
use common\models\UserRelation;
use common\models\SalesEventHandler;
use common\models\MemberRankEventHandler;
use common\models\UserOperation;
use common\models\MembersOrder;
use common\models\Order;
use common\models\AutoPlaceEventHandler;
use common\models\MonthFee;
use common\models\AwardPoints;
use common\models\AwardCommonUtil;
use common\models\MessageEventHandler;
use common\models\Message;
use common\models\AwardTotal;

class DayCheckController extends Controller{
    
    public function actionIndex(){
      echo  CommonUtil::LogMsg('每天运行时间');
    }
    
    /**
     * 
     * @author youngshunf
     */
    //----------------------------------------------用户状态更新--------------------------------------------
    public function actionUserStatus() {
    //用户是否过了犹豫期判断
        $i=0;
        // 一次提取 10 个用户并一个一个地遍历处理，减少内存占用
        foreach (User::find()->andWhere("status=1  or status=12 ")->each(10) as $user) {
            $registerTime=$user['insert_time'];
            $hesitationPeriod=3600*24*14;//犹豫期       
            if((time()-$registerTime)>=$hesitationPeriod){
                UserOperation::updateUserStatus($user['user_guid'], CommonUtil::USER_NORMAL);
                $i++;
                echo CommonUtil::LogMsg("过了犹豫期",$user['real_name']);
            }
        }
    
        $j=0;
        //超过3天未支付用户停用
        foreach (User::find()->andWhere(['status'=>CommonUtil::USER_NOT_PAY,'is_member'=>'1'])->each(10) as $user){
            $registerTime=$user['insert_time'];
            $stopPeriod=3600*24*3;
            $leftTime=time()-$registerTime;
         
            if($leftTime>=$stopPeriod){
                User::updateAll(['enable'=>0],['user_guid'=>$user->user_guid]);
                echo CommonUtil::LogMsg('用户已停用',$user->user_guid);
                $j++;
            }
        }
        echo CommonUtil::LogMsg("更新用户状态成功,有".$i."个用户过了犹豫期".$j."个用户已被停用");
    }
   
    //-----------------------------------------------------个人销售奖(翅膀奖)计算-----------------------------
    public function actionSales(){
   
        $i=0;
        $salesEventHandler=new SalesEventHandler();
        //绑定事件
        $this->on(CommonUtil::SALES_AWARD, [$salesEventHandler,SalesEventHandler::SALES_AWARD_HANDLER]);
        // 一次提取 10 个用户并一个一个地遍历处理，减少内存占用
       foreach (User::find()->each(10) as $user){
           $latestAward=SalesAward::find()->andWhere(['user_guid'=>$user['user_guid']])->orderBy("insert_time desc")->one();
           $startTime="";
           //如果未获得个人销售奖，则从推荐第一个用户开始计算
            if(empty($latestAward)){
                $userRelation=UserRelation::find()->andWhere(['user_guid'=>$user['user_guid']])->orderBy("insert_time asc")->one();
                if(empty($userRelation)){
                 echo CommonUtil::LogMsg("未发展下线，不计算个人销售奖",$user['username']);
                 continue;
                }
                $startTime=$userRelation['insert_time'];
            }else{
                //获得个人销售奖，从上一次个人销售奖结束时间开始计算
                $startTime=$latestAward['end_time'];
            }           
       
            $endTime=time();
            $awardPeriod=3600*24*28;//个人销售奖计算周期，28天
            if(($endTime-$startTime)>=$awardPeriod){                
                $salesEventHandler->start_time=$startTime;
                $salesEventHandler->end_time=$endTime;
                $salesEventHandler->user_guid=$user['user_guid'];              
                if($this->trigger(CommonUtil::SALES_AWARD,$salesEventHandler)){
                   $i++;
                   echo CommonUtil::LogMsg("获得一次个人销售奖",$user['username']);
                }
                
            }
          
       }
       
       //计算完毕解绑事件
       $this->off(CommonUtil::SALES_AWARD, [$salesEventHandler,SalesEventHandler::SALES_AWARD_HANDLER]);
       echo CommonUtil::LogMsg("个人销售奖计算完成,有".$i."个用户获得个人销售奖");       
    }
    
    //--------------------------------------------------------------更新用户等级-------------------------------------------------------
    public function actionUpdateUserRank(){
        $i=0;
        $memberRankHandler=new MemberRankEventHandler();
        $this->on(CommonUtil::MEMBER_RANK,[$memberRankHandler,MemberRankEventHandler::MEMBER_RANK_HANDLER]);
        foreach (User::find()->each(10) as $user){           
            $memberRankHandler->user_guid=$user['user_guid'];          
            $this->trigger(CommonUtil::MEMBER_RANK,$memberRankHandler);
            $i++;           
        }       
        $this->off(CommonUtil::MEMBER_RANK,[$memberRankHandler,MemberRankEventHandler::MEMBER_RANK_HANDLER]);
        echo CommonUtil::LogMsg("会员等级更新计算完成,有".$i."个用户计算了会员等级");
    }
    

    
    /**
     * @author ysf
     * 月费计算，每天对SR级别以下的用户进行月费计算，如果月费支付超过一个月，则产生新的月费订单
     */
    public function actionMonthFeeCalculate(){
     $i=0;
     $SR=CommonUtil::RANK_SR;
     $message=new MessageEventHandler();
     $this->on(MessageEventHandler::MESSAGE_EVENT_HANDLER, [$message,MessageEventHandler::MESSAGE_EVENT_HANDLER]);
     
     foreach (User::find()->andWhere("role_id =1 ")->each(10) as $user){
            $user_guid=$user->user_guid;
               $monthFee=MonthFee::find()->andWhere(['user_guid'=>$user->user_guid])->orderBy('insert_time desc')->one();
                if(empty($monthFee)){
                 CommonUtil::LogMsg("用户没有月费订单",$user->real_name);
                 break;
                }
                  $lastStartTime=$monthFee->start_time;
                  $lastEndTime=$monthFee->end_time;                    
                  //$lastEndTime=strtotime("2015-07-20");
                  $nextStartTime=date("Y-m-d",strtotime("$lastStartTime +1 day"));
                  $nextEndTime=date("Y-m-d",strtotime("$nextStartTime +1 month"));
                  if(time()<$lastEndTime){  
                       CommonUtil::LogMsg("用户订单未到期",$user->real_name);
                       break;
                  }                
                      $normalUsers=UserRelation::find()->andWhere( "user_guid  ='$user_guid' and lower_user_status=2  " )->count();
                       if($normalUsers<4){
                       $month_fee=CommonUtil::PLATINUM_MONTHLY_FEE;                                                
                      $newMemberOrder=new MembersOrder();
                      $newMemberOrder->user_guid=$user_guid;
                      $newMemberOrder->order_guid=CommonUtil::createUuid();
                      $newMemberOrder->order_num=CommonUtil::getOrderNum("MT", $user->id);                 
                      $newMemberOrder->start_time=$nextStartTime;
                      $newMemberOrder->end_time=$nextEndTime;
                      $newMemberOrder->amount=$month_fee;
                      $newMemberOrder->order_time=time();
                      $newMemberOrder->insert_time=time();
                      $newMemberOrder->type=2;
                      $newMemberOrder->remark="您的直推活跃用户数为".$normalUsers;              
                     $newMemberOrder->save();                     
                    
                                           
                      $order=new Order();
                      $order->user_guid=$user_guid;
                      $order->order_guid=$newMemberOrder->order_guid;
                      $order->order_num=$newMemberOrder->order_num;
                      $order->type=2;
                      $order->consume_amount=$newMemberOrder->amount;
                      $order->insert_time=time();
                      $order->save();
                        
                      $newMonthFee=new MonthFee();
                      $newMonthFee->user_guid=$user_guid;
                      $newMonthFee->year_month=date('Ym');
                      $newMonthFee->order_guid=$newMemberOrder->order_guid;
                      $newMonthFee->start_time=strtotime($newMemberOrder->start_time);
                      $newMonthFee->end_time=strtotime($newMemberOrder->end_time);
                      $newMonthFee->month_fee=$month_fee;
                      $newMonthFee->active_member=$normalUsers;
                      $newMonthFee->insert_time=time();
                      $newMonthFee->save();
                      
                  
                      $message->to_user=$user_guid;
                      $message->title="您的月费已到期,请尽快交月费";
                      $message->type=CommonUtil::SYS_MESSAGE;
                      $message->content="您的月费在".CommonUtil::fomatDate($lastEndTime)."已到期,本月未满足免月费条件,需缴纳月费".$month_fee.".请尽快到<a href='http://tourgm.com/order/member'>订单管理</a>页面进行月费缴纳";                  
                      $this->trigger(MessageEventHandler::MESSAGE_EVENT_HANDLER,$message);
                      
                      $this->off(MessageEventHandler::MESSAGE_EVENT_HANDLER,$message);
                    }else{
                      //  echo "活跃用户数超过4个";die;
                        //活跃用户数超过4个免月费
                        $newMonthFee=new MonthFee();
                        $newMonthFee->year_month=date("Ym");
                        $newMonthFee->user_guid=$user_guid;                     
                        $newMonthFee->start_time=strtotime($nextStartTime);
                        $newMonthFee->end_time=strtotime($nextEndTime);
                        $newMonthFee->month_fee=0;
                        $newMonthFee->active_member=$normalUsers;
                        $newMonthFee->is_free=1;
                        $newMonthFee->insert_time=time();
                        $newMonthFee->save();
                        
                        //奖励10个冻结积分
                        $points=new AwardPoints();
                        $points->user_guid=$user_guid;
                        $points->award_point_guid=CommonUtil::createUuid();
                        $points->bonus_points_frozen=AwardCommonUtil::MONTH_FEE_FROZEN_POINTS;
                        $points->bonus_points_type=AwardCommonUtil::MONTH_AWARD_TYPE;
                        $points->enable_time=strtotime("+".AwardCommonUtil::REGISTER_ENABLE_TIME." year");
                        $points->insert_time=time();
                        $points->save();
                        
                        $awardTotal=AwardTotal::findOne(['user_guid'=>$user_guid]);
                        if(empty($awardTotal)){
                            $awardTotal=new AwardTotal();
                            $awardTotal->user_guid=$user_guid;
                        }
                        $awardTotal->total_award_points_frozen_pre +=$points->bonus_points_frozen;
                        $awardTotal->save();
                        
                                           
                        $message->to_user=$user_guid;
                        $message->title="您本月月费已免,并获得".$points->bonus_points_frozen."积分";
                        $message->type=CommonUtil::SYS_MESSAGE;
                        $message->content="您本月的活跃会员数为".$newMonthFee->active_member.",月费已免.并获得".$points->bonus_points_frozen."积分,积分可用时间:".CommonUtil::fomatDate($points->enable_time);
                        $this->trigger(MessageEventHandler::MESSAGE_EVENT_HANDLER,$message);
                        $this->off(MessageEventHandler::MESSAGE_EVENT_HANDLER,$message);
                      }  
                      $i++;
                  
                
        }
        
        CommonUtil::LogMsg("总共有".$i."个用户计算了月费");
    }
    
      /**
         * The descriptions of functions.
         * @desc: 直推会员超过3天未码放，自动码放
          * @author: youngshunf
          * @date: 2015年6月10日
         * @access public
         * @return void
         */
   public  function actionAutoPlaced() {
       $autoPlaced=new AutoPlaceEventHandler();
       $this->on(AutoPlaceEventHandler::AUTO_PLACE_HANDLER, [$autoPlaced,AutoPlaceEventHandler::AUTO_PLACE_HANDLER]);
       $i=0;
       foreach (UserRelation::find()->andWhere("is_placed=0 and lower_user_status>0")->each(10) as $unPlacedUser){
           $threeDay=3600*24*3;//3天
           if(time()-$unPlacedUser->insert_time>=$threeDay){          
               $autoPlaced->user_guid=$unPlacedUser->lower_user;            
               $this->trigger(AutoPlaceEventHandler::AUTO_PLACE_HANDLER,$autoPlaced);               
               $i++;
           }
       }
       CommonUtil::LogMsg("总共有".$i."个用户被自动码放了位置");
    }
}