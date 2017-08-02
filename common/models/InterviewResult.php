<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_result".
 *
 * @property integer $id
 * @property string $name
 * @property string $id_code
 * @property string $level
 * @property string $rec_work_number
 * @property string $rec_name
 * @property string $interview_time
 * @property string $interview_result
 * @property string $train_result
 * @property string $status
 * @property string $remark
 * @property integer $created_at
 */
class InterviewResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['name', 'rec_name', 'interview_time'], 'string', 'max' => 12],
            [['id_code'], 'string', 'max' => 24],
            [['level', 'rec_work_number'], 'string', 'max' => 20],
            [['interview_result', 'train_result'], 'string', 'max' => 48],
            [['status', 'remark'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'id_code' => '身份证号',
            'level' => '入职定级',
            'rec_work_number' => '推荐人系统号',
            'rec_name' => '推荐人姓名',
            'interview_time' => '面试时间',
            'interview_result' => '面试结果',
            'train_result' => '培训结果',
            'status' => '入职状态',
            'remark' => '备注',
            'created_at' => '导入时间',
        ];
    }
}
