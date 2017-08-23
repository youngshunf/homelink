<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property integer $card_id
 * @property string $user_guid
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $district
 * @property string $shop
 * @property string $business_circle
 * @property string $building
 * @property string $address
 * @property string $sign
 * @property string $path
 * @property string $photo
 * @property string $template
 * @property string $created_at
 * @property string $updated_at
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','mobile','email','district','shop','business_circle','building','address','sign'], 'required'],
            ['email','email'],
            ['mobile','match','pattern'=>'^[1][3-8]+\\d{9}$^','message'=>'请输入正确的手机号码'],
            [['mobile'], 'string','max'=>11, 'min'=>11, 'tooLong'=>'手机号不能大于11位', 'tooShort'=>'手机号不能小于11位'],        
      /*       [['name', 'business_circle','shop'], 'string', 'max' => 255],
            [['mobile', 'district',  'template'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 255],
            [['building', 'address', 'path', 'photo'], 'string', 'max' => 255] */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'card_id' => 'Card ID',
            'user_guid' => 'User Guid',
            'name' => '姓名',
            'mobile' => '电话',
            'email' => '邮箱',
            'district' => '大区',
            'shop' => '店面',
            'business_circle' => '负责商圈',
            'building' => '负责楼盘',
            'address' => '地址',
            'sign' => '个人简介',
            'score'=>'星级',
            'path' => 'Path',
            'photo' => 'Photo',
            'template' => '模板',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
