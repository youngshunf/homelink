<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "growth_rec".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $work_number
 * @property string $items
 * @property string $item_time
 * @property string $district
 * @property string $honor
 * @property string $award
 * @property string $training
 * @property integer $task_do
 * @property integer $task_complete
 * @property double $score
 * @property string $created_at
 * @property string $updated_at
 */
class GrowthRec extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'growth_rec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
      /*       [['item_time', 'task_do', 'task_complete', 'created_at', 'updated_at'], 'integer'],
            [['score'], 'number'],
            [['user_guid', 'work_number'], 'string', 'max' => 48],
            [['items', 'honor', 'award', 'training'], 'string', 'max' => 512],
            [['district'], 'string', 'max' => 128] */
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
            'work_number' => '工号',
            'items' => '事项',
            'item_time' => '日期',
            'district' => 'District',
            'honor' => '荣誉',
            'award' => '奖励',
            'training' => '培训',
            'classname'=>'班级',
            'task_do' => 'Task Do',
            'task_complete' => 'Task Complete',
            'score' => '学分',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(AuthUser::className(), ['work_number'=>'work_number']);
    }
}
