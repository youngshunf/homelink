<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_result".
 *
 * @property integer $id
 * @property integer $task_id
 * @property string $work_number
 * @property string $user_guid
 * @property string $district
 * @property string $comment_user
 * @property integer $score
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class TaskResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['comment', 'score'], 'required','on'=>['comment']],
            [['task_id', 'score', 'created_at', 'updated_at','comment_result'], 'integer'],
            [['work_number'], 'string', 'max' => 64],
            [['user_guid', 'comment_user'], 'string', 'max' => 48],
            [['business_district'], 'string', 'max' => 128],
            [['comment'], 'string']
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
            'work_number' => '工号',
            'user_guid' => 'User Guid',
            'business_district' => '所属大区',
            'comment_user' => '打分用户',
            'comment_result'=>'任务完成情况',
            'score' => '获得分数',
            'comment' => '评价',
            'created_at' => '评论时间',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'user_guid']);
    }
    
    public function getCommentUser(){
        return $this->hasOne(User::className(), ['user_guid'=>'comment_user']);
    }
    
    public function getTask(){
        return $this->hasOne(Task::className(), ['id'=>'task_id']);
    }
}
