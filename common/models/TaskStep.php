<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_step".
 *
 * @property integer $id
 * @property integer $task_id
 * @property integer $step
 * @property integer $score
 * @property integer $status
 * @property integer $type
 * @property string $title
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class TaskStep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_step';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'step', 'score', 'status', 'type', 'created_at', 'updated_at'], 'integer'],
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
            'task_id' => 'Task ID',
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
