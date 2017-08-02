<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $template_id
 * @property integer $group_id
 * @property string $name
 * @property string $url
 * @property string $created_at
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'created_at'], 'integer'],
            [['user_guid'], 'string', 'max' => 48],
            [['template_id', 'name', 'url'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_guid' => 'User Guid',
            'template_id' => '模板ID',
            'group_id' => '分组ID',
            'name' => '模板名称',
            'url' => '跳转链接',
            'created_at' => 'Created At',
        ];
    }
    
    public function getGroup(){
        return $this->hasOne(UserGroup::className(), ['id'=>'group_id']);
    }
}
