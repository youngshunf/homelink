<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "card_comment".
 *
 * @property integer $comment_id
 * @property string $user_guid
 * @property integer $card_id
 * @property string $content
 * @property integer $score
 * @property string $ip
 * @property string $created_at
 */
class CardComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
              [['content'], 'required'],
            [['content'], 'string'],
            [['user_guid'], 'string', 'max' => 48],
            [['ip'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'user_guid' => 'User Guid',
            'card_id' => 'Card ID',
            'content' => '评论内容',
            'score' => '得分',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }
    
    public function getUser(){
        return  $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
}


