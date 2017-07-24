<?php
namespace console\controllers;
use yii\console\Controller;
use common\models\Order;
use common\models\ProductOrder;
use common\models\User;
use common\models\UserRelation;

class TempController extends Controller{
    
    public function actionIndex(){
        echo "临时函数";
    }
    
    public function actionUpdateOrder(){        
        foreach ( Order::find()->andWhere("type=1")->each(10) as $order){
            $productOrder=ProductOrder::findOne(['order_guid'=>$order->order_guid]);
            $order->product_guid=$productOrder->product_guid;
            $order->save();
        }
    }
    
    public function actionUpdateInviteCode(){
        
        foreach (User::find()->each(10) as $user){
            $user->serial_invite_code=$user->invite_code;
            $user->save();
        }
        
    }
    
    /**
     * 清理已删除用户的关系
     */
    
    public function  actionCleanUser(){
        foreach (User::find()->each(10) as $user){
            //清理用户已经删除的下级用户
            $userRelation=UserRelation::findAll(['user_guid'=>$user->user_guid]);
            foreach ($userRelation as $v){
                $downUser=User::findOne(['user_guid'=>$v->lower_user]);
                if($downUser===null){
                    $v->delete();
                }
            }
        }
    }
    
    /**
     * @author shunfu.yang
     * 更新用户表中直推会员个数,此函数只需运行一次
     */
    
    public function  actionUpdateDirectNumber(){
        foreach (User::find()->each(10) as $user){
            //清理用户已经删除的下级用户
            $userRelation=UserRelation::findAll(['user_guid'=>$user->user_guid]);
            foreach ($userRelation as $v){
                $downUser=User::findOne(['user_guid'=>$v->lower_user]);
                if($downUser===null){
                    $v->delete();
                }
            }
    
            $user_guid=$user->user_guid;
            $directNumber=UserRelation::find()->andWhere(" user_guid = '$user_guid' and lower_user_status!=0")->count();
            $user->direct_number=$directNumber;
            $user->save();
    
        }
    }

    
}