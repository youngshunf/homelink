<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "advice".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $title
 * @property string $content
 * @property string $created_at
 */
class Advice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content'], 'required'],
            [['content'], 'string'],
            [['created_at'], 'integer'],
            [['user_guid'], 'string', 'max' => 48],
            [['title'], 'string', 'max' => 1024]
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
            'title' => '标题',
            'content' => '内容',
            'created_at' => 'Created At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}
