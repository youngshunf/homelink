<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vote_item".
 *
 * @property integer $vote_item_id
 * @property integer $vote_id
 * @property string $title
 * @property string $content
 * @property string $photo
 * @property integer $vote_number
 * @property string $created_at
 * @property string $updated_at
 */
class VoteItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vote_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content','title'], 'required','on'=>'create'],
            [['vote_id', 'vote_number', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title', 'photo'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vote_item_id' => 'Vote Item ID',
            'vote_id' => 'Vote ID',
            'title' => '标题',
            'content' => '内容',
            'photo' => '图片',
            'type'=>'题型',
            'vote_number' => '投票人数',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
