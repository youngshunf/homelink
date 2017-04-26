<?php
namespace common\models;

use yii\web\UploadedFile;
class ImageUploader{
    
    /**
     * 上传图片文件
     * @param string $name
     */
    public static function uploadByName($name){
        $photoFile=UploadedFile::getInstanceByName('photo');
        if(empty($photoFile)){
            return false;
        }
            $basePath='../../upload/photo/';
            $path=date('Ymd').'/';
            if(!is_dir($basePath.$path)){
                mkdir($basePath.$path);
            }
            $fileName=date('YmdHis').rand(0000, 9999).'.'.$photoFile->extension;
            $photoFile->saveAs($basePath.$path.$fileName);
        
            $thumbDir=$basePath.$path.'thumb/';
            if(!is_dir($thumbDir)){
                mkdir($thumbDir);
            }
            if(is_file($basePath.$path.$fileName)){
                $thumb=new Image($basePath.$path.$fileName, $thumbDir.$fileName);
                $thumb->thumb(150,150);
                $thumb->out();
            }
        
            $standardDir=$basePath.$path.'standard/';
            if(!is_dir($standardDir)){
                mkdir($standardDir);
            }
            if(is_file($basePath.$path.$fileName)){
                $thumb=new Image($basePath.$path.$fileName, $standardDir.$fileName);
                $thumb->thumb(720,400);
                $thumb->out();
            }
            
            return ['path'=>$path,'photo'=>$fileName];
        
    }
    
    public static function uploadPhotoThumb($name,$path,$fileName){
        $photoFile=UploadedFile::getInstanceByName('photoThumb');
        if(empty($photoFile)){
            return false;
        }
        $basePath='../../upload/photo/';
        
        if(!is_dir($basePath.$path)){
            mkdir($basePath.$path);
        }
        $thumbDir=$basePath.$path.'thumb/';
        $photoFile->saveAs($thumbDir.$fileName);
    
       
        if(!is_dir($thumbDir)){
            mkdir($thumbDir);
        }
        if(is_file($basePath.$path.$fileName)){
            $thumb=new Image($thumbDir.$fileName, $thumbDir.$fileName);
            $thumb->thumb(150,150);
            $thumb->out();
            return true;
        }
       return false;
    
    }
    
   public  static function  uploadImageByBase64($imgData,$imgLen){
     
       if(strlen($imgData)!=$imgLen){
           return false;
       }
       $basePath='../../upload/photo/';
       $path=date('Ymd').'/';
       if(!is_dir($basePath.$path)){
           mkdir($basePath.$path);
       }
       $filePath=$basePath.$path;
       $file=date('YmdHis').rand(0000, 9999);
       
       $fileName=self::base64ToImg($imgData, $filePath, $file);
       
       $thumbDir=$basePath.$path.'thumb/';
       if(!is_dir($thumbDir)){
           mkdir($thumbDir);
       }    
       $standardDir=$basePath.$path.'standard/';
       if(!is_dir($standardDir)){
           mkdir($standardDir);
       }
       if(is_file($basePath.$path.$fileName)){
           //生成缩略图
           $thumb=new Image($basePath.$path.$fileName, $thumbDir.$fileName);
           $thumb->thumb(150,150);
           $thumb->out();
           
           //生成标准图
           $thumb=new Image($basePath.$path.$fileName, $standardDir.$fileName);
           $thumb->thumb(720,400);
           $thumb->out();
       }
       
       return ['path'=>$path,'photo'=>$fileName];
   }
    
    /**
     * 将base64编码转为图片格式存储
     * @param string $imgData
     * @param string $filePath
     * @param string $fileName
     * @return string|boolean
     */
    public static   function base64ToImg($imgData,$filePath,$fileName){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgData, $result)){
            $type = $result[2];
            $new_file =$filePath.$fileName.'.'.$type;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imgData)))){
                return $fileName.'.'.$type;
            }
        }
        return false;
    }
    
}