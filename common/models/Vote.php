<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vote".
 *
 * @property integer $vote_id
 * @property string $title
 * @property string $content
 * @property string $photo
 * @property string $start_time
 * @property string $end_time
 * @property integer $vote_number
 * @property string $created_at
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content','start_time','end_time'],'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 64],
            [['photo'], 'file', 'extensions' => 'jpg,png,gif']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vote_id' => 'Vote ID',
            'title' => '标题',
            'content' => '描述',
            'photo' => '图片',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'vote_number' => '投票人数',
            'created_at' => '创建时间',
        ];
    }
}
