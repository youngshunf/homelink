<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_data".
 *
 * @property integer $id
 * @property string $level
 * @property string $mobile
 * @property string $sale_district
 * @property string $business_district
 * @property string $shop
 * @property string $age
 * @property string $marriage
 * @property string $join_date
 * @property string $top_edu
 * @property string $teacher
 * @property string $score
 * @property string $qual
 * @property string $year_yellow
 * @property string $year_sue
 * @property string $half_score
 * @property string $half_range
 * @property string $co_single
 * @property string $co_single_range
 * @property string $half_qual
 * @property string $half_record
 * @property string $year_month
 * @property integer $created_at
 */
class InterviewData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview_data';
    }

    /**
     * @inheritdoc
     */
//     public function rules()
//     {
//         return [
//             [['created_at'], 'integer'],
//             [['level', 'mobile'], 'string', 'max' => 20],
//             [['sale_district', 'business_district', 'shop', 'age', 'marriage', 'join_date', 'top_edu', 'teacher', 'score', 'qual', 'year_yellow', 'year_sue', 'half_score', 'half_range', 'co_single', 'co_single_range', 'half_qual', 'half_record', 'year_month'], 'string', 'max' => 255]
//         ];
//     }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_number'=>'工号',
            'name'=>'姓名',
            'level' => '级别',
            'mobile' => '联系方式',
            'sale_district' => '营销区域',
            'business_district' => '业务区域',
            'shop' => '门店',
            'age' => '年龄',
            'marriage' => '婚姻状况',
            'join_date' => '入职日期',
            'top_edu' => '最高教育程度',
            'teacher' => '认证讲师',
            'score' => '博学成绩',
            'qual' => '精英社资格',
            'year_yellow' => '一年内黄线',
            'year_sue' => '一年内投诉',
            'half_score' => '半年业绩',
            'half_range' => '半年业绩大区排名',
            'co_single' => '合作单边比',
            'co_single_range' => '合作单边比大区排名',
            'half_qual' => '半年带看质量',
            'half_record' => '半年录入客户量',
            'year_month' => 'Year Month',
            'created_at' => 'Created At',
        ];
    }
}
