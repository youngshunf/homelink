<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "user_group".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $group_name
 * @property string $created_at
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
      
            [['user_guid'], 'string', 'max' => 48],
            [['group_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '分组ID',
            'user_guid' => '创建用户',
            'group_name' => '分组名',
            'created_at' => 'Created At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(AdminUser::className(), ['user_guid'=>'user_guid']);
    }
}
