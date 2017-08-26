<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\CommonUtil;
use common\models\Category;
use common\models\Image;
/**
 * Register form
 */
class ImgCateForm extends Model
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
        $cate = Category::findOne(['id'=>$id]);
        $imagepath = "";
        if(empty($cate['photo'])){
            $cate['photo']=CommonUtil::createUuid();
        }
        $imgPath = yii::$app->basePath."../../upload/product_img/".$cate['photo'];
        $thumbPath = yii::$app->basePath."../../upload/product_img/thumbnails/".$cate['photo'];
        if (!empty($cate['photo'])) {        
        	@unlink($imgPath);
        	@unlink($thumbPath);
        }
        
        $cate->photo = UploadedFile::getInstance($this, 'photo');
        $imagepath = CommonUtil::createUuid() . '.' . $cate->photo->extension;
        $cate->photo->saveAs(yii::$app->basePath . '../../upload/product_img/'. $imagepath);
        $cate->photo = $imagepath;
        	
        $savePath=yii::$app->basePath."../../upload/product_img/";
        $thumbPath=$savePath.'/thumbnails/';
        
        if (!file_exists($thumbPath)) {
            mkdir($thumbPath);
        }
        $dst_img=$thumbPath.'/'.$imagepath;
        $thumb=new Image($savePath."/".$imagepath, $dst_img);
        $thumb->thumb(400,300);
        $thumb->out();
        
        
        if ($cate->save()){
        	return true;
        }
    	return false;
    }
}
