<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_district".
 *
 * @property integer $id
 * @property string $district_code
 * @property string $district_name
 * @property string $assistant_number
 * @property string $assistant_name
 * @property string $supervisor_number
 * @property string $supervisor_name
 * @property integer $created_at
 */
class InterviewDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['district_code', 'district_name', 'assistant_number', 'assistant_name', 'supervisor_number', 'supervisor_name'], 'safe']
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
            'assistant_number' => '大区助理工号',
            'assistant_name' => '大区助理姓名',
            'supervisor_number' => '总监工号',
            'supervisor_name' => '总监姓名',
            'created_at' => '创建时间',
        ];
    }
}
