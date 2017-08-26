<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_register".
 *
 * @property integer $id
 * @property string $district_code
 * @property string $district_name
 * @property integer $activity_id
 * @property string $year_month
 * @property string $work_number
 * @property string $name
 * @property string $mobile
 * @property integer $signup_result
 * @property integer $interview_result
 * @property string $remark
 * @property integer $is_appeal
 * @property integer $created_at
 */
class InterviewRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'signup_result', 'interview_result', 'is_appeal', 'created_at'], 'integer'],
            [['district_code', 'district_name'], 'string', 'max' => 64],
            [['year_month', 'work_number', 'name', 'mobile'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'district_code' => '大区编号',
            'district_name' => '大区名',
            'activity_id' => '活动ID',
            'year_month' => '月份',
            'work_number' => '工号',
            'name' => '姓名',
            'mobile' => '手机',
            'signup_result' => '报名结果',
            'interview_result' => '面试结果',
            'remark' => '面试评价',
            'is_appeal' => '申诉状态',
            'appeal_remark' => '申诉处理意见',
            'created_at' => '创建时间',
        ];
    }
}
