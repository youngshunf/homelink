<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_address".
 *
 * @property integer $id
 * @property string $district_code
 * @property string $district_name
 * @property string $year_month
 * @property integer $time
 * @property string $address
 * @property string $created_at
 */
class InterviewAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year_month','time','address'],'required'],
//             [['district_code', 'district_name', 'year_month', 'address', 'created_at','time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'district_code' => '大区编号',
            'district_name' => '大区名',
            'year_month' => '月份',
            'time' => '面试时间',
            'address' => '面试地点',
            'created_at' => 'Created At',
        ];
    }
}
