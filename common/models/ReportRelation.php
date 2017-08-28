<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_relation".
 *
 * @property integer $id
 * @property integer $reportid
 * @property string $work_number
 * @property string $do_work_number
 * @property integer $type
 * @property string $answer
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReportRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reportid', 'type', 'created_at', 'updated_at'], 'integer'],
            [['answer'], 'string'],
            [['work_number', 'do_work_number'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reportid' => 'Reportid',
            'work_number' => '被评价人工号',
            'do_work_number' => '评价人工号',
            'type' => '评价组(类型)',
            'answer' => '评价结果',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    public function getBeuser(){
        return $this->hasOne(User::className(), ['work_number'=>'work_number']);
    }
    public function getDouser(){
        return $this->hasOne(User::className(), ['work_number'=>'do_work_number']);
    }
}
