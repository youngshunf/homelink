<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use common\models\WeChatTools;
/**
 * Login form
 */
class CardSearch extends Model
{
    public $name;
    public $district;
    public $business_circle;
    public $shop;
    public $building;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
               [[ 'name',  'district','business_circle', 'shop','building'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'name' => '姓名',
            'district'=>'工号' ,
            'shop'=>'店面',
            'businessCircle'=>'商圈',
            'building'=>'楼盘'
        ];
    }


    public function getResult()
    {
        if(!$this->validate()){
            return false;
        }
        $data=[
            'name'=>$this->name,
            'district'=>$this->district,
            'shop'=>$this->shop,
            'businessCircle'=>$this->business_circle,
            'building'=>$this->building
        ];
        $url="http://www.3meima.com:8080/searchExcellent.do";
        
        return WeChatTools::httpsRequest($url,$data);
    }
    
    public function http_post($data) {
        $url="http://www.3meima.com:8080/searchExcellent.do";
       $ch = curl_init ();
       $this_header = array(
           "content-type: application/x-www-form-urlencoded;
            charset=UTF-8"
       );
        curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, count($data) );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        return Json::decode($return,true);
    }
}
