<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
use common\models\CommonUtil;
use common\models\Product;
use common\models\Image;
/**
 * Register form
 */
class ImgForm extends Model
{
    public $photo;
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [          
        [['photo'], 'required','message'=>'不能为空'],
        [['photo'], 'file','extensions' => 'gif, jpg, png',]
        ];
    }

    /**
     * 注册
     *
     */
    public function update($id)
    {	
        $product = Product::findOne(['id'=>$id]);
        $imagepath = "";
        
        if (!empty($product['photo'])) {
        	$imgPath = yii::$app->basePath."../../upload/product_img/".$product['photo'];
        	$imgPath = yii::$app->basePath."../../upload/product_img/thumbnails/".$product['photo'];
        	@unlink($imgPath);
        }else{
    
        }
        $product->photo = UploadedFile::getInstance($this, 'photo');
        $imagepath = CommonUtil::createUuid() . '.' . $product->photo->extension;
        $savePath=yii::$app->basePath . '../../upload/product_img/';
        $product->photo->saveAs($savePath.'/'. $imagepath);
        $product->photo = $imagepath;
        $thumbPath=$savePath.'/thumbnails/';
        
        if (!file_exists($thumbPath)) {
            mkdir($thumbPath);
        }
        $dst_img=$thumbPath.'/'.$imagepath;
        $thumb=new Image($savePath."/".$imagepath, $dst_img);
        $thumb->thumb(400,300);
        $thumb->out();
        
        if ($product->save()){
        	return true;
        }
    	return false;
    }
}
