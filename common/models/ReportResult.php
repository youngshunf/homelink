<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_result".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $work_number
 * @property integer $reportid
 * @property string $name
 * @property string $desc
 * @property string $path
 * @property string $photo
 * @property integer $report_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReportResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_number', 'report_time'], 'required'],
            [['reportid','created_at', 'updated_at','pid'], 'integer'],
            [['desc'], 'string'],
            [['user_guid', 'work_number'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['path', 'photo'], 'string', 'max' => 128]
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
            'reportid' => 'Reportid',
            'name' => '报告名称',
            'desc' => '报告描述',
            'path' => 'path',
            'photo' => 'photo',
            'report_time' => '报告可查看时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    public function getUser(){
        return $this->hasOne(User::className(), ['work_number'=>'work_number']);
    }
}
