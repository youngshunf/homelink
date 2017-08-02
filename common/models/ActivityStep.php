<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_step".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $step
 * @property integer $score
 * @property integer $status
 * @property integer $type
 * @property string $title
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class ActivityStep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_step';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'step', 'score', 'status', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'step' => 'Step',
            'score' => '学分',
            'status' => 'Status',
            'type' => '类型',
            'title' => 'Title',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
