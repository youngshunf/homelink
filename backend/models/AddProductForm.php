<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
use common\models\CommonUtil;
use common\models\Product;
/**
 * Register form
 */
class AddProductForm extends Model
{
    public $name;
    public $desc;
    public $photo;
    public $prise;
    public $destination_country;
    public $start_time;
    public $end_time;
    public $signup_endtime;
    public $max_number;
    public $is_member;
    public $members_points;
    public $is_hotel;
    public $is_restaurant;
    public $is_car;
    public $is_train;
    public $is_plane;
    public $short_name;
    public $keywords;
    public $sort;
    public $is_recommend;
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [          
         [['is_recommend','sort','keywords','short_name','is_member','members_points','is_hotel','is_restaurant','is_car','is_train','is_plane','name','desc','photo','prise','destination','start_time','end_time','signup_endtime','max_number'], 'required','message'=>'不能为空'],
            [['desc'], 'string'],
            [['prise'], 'number'],
            [['start_time', 'end_time', 'signup_endtime'], 'safe'],
            [['photo'], 'string', 'max' => 48],
            [['name', 'destination'], 'string', 'max' => 255],
            [['photo'], 'file','extensions' => 'gif, jpg, png']
        ];
    }

    /**
     * 添加产品
     *
     */
    public function save($start_time,$end_time,$signup_endtime,$cate_guid)
    {
    	$product = new Product();
    	$product->product_guid = CommonUtil::createUuid();
    	$product->cate_guid = $cate_guid;
    	$product->name = $this->name;
    	$product->short_name = $this->short_name;
    	$product->keywords = $this->keywords;
    	$product->sort = $this->sort;
    	$product->desc = $this->desc;
    	$product->prise = $this->prise;
    	$product->destination_country = $this->destination_country;
    	$product->start_time = $start_time;
    	$product->end_time = $end_time;
    	$product->signup_endtime = $signup_endtime;
    	$product->max_number = $this->max_number;
    	$product->is_recommend = $this->is_recommend;
    	$product->is_member = $this->is_member;
    	$product->members_points = $this->members_points;
    	$product->is_hotel = $this->is_hotel;
    	$product->is_restaurant = $this->is_restaurant;
    	$product->is_car = $this->is_car;
    	$product->is_train = $this->is_train;
    	$product->is_plane = $this->is_plane;
    	$product->insert_time = time();
    	$imagepath = "";
    	$product->photo = UploadedFile::getInstance($this, 'photo');
    	$imagepath = CommonUtil::createUuid() . '.' . $product->photo->extension;
    	$product->photo->saveAs(yii::$app->basePath . '/../upload/photo/'.$cate_guid.'/'. $imagepath);
    	$product->photo = $imagepath;	
        if ($product->save()){
        	return true;
        }	
    	return false;
    }
}
