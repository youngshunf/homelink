<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
use common\models\CommonUtil;
/**
 * Register form
 */
class ImgForm extends Model
{
    public $img_path;
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [          
        [['img_path'], 'required','message'=>'不能为空'],
        [['img_path'], 'file','extensions' => 'gif, jpg, png',]
        ];
    }

    /**
     * 注册
     *
     */
    public function update($id)
    {
    		
        	$user= User::findOne(['id'=>$id]);
        	$imagepath = "";
        	
        	$user->img_path = UploadedFile::getInstance($this, 'img_path');
      
        	if (!empty($user->img_path)) {
        		
        		if (!empty($user['img_path'])) {
        			$imgPath = yii::$app->basePath."../../upload/avatar/".$user['img_path'];
        			@unlink($imgPath);
        		}
        		$imagepath = CommonUtil::createUuid() . '.' . $user->img_path->extension;
        		$user->img_path->saveAs(yii::$app->basePath . '../../upload/avatar/'. $imagepath);
        		$user->img_path = $imagepath;
        		
        		if ($user->save()){
        			return true;
        		}
        	}
        	
    		
    		return false;
    }

}
