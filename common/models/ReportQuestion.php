<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_question".
 *
 * @property integer $id
 * @property integer $reportid
 * @property integer $type
 * @property string $name
 * @property string $question
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReportQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['reportid', 'type', 'created_at', 'updated_at'], 'integer'],
            [['question'], 'string'],
            [['name'], 'string', 'max' => 255]
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
            'type' => '问卷类型',
            'name' => '问卷名称',
            'question' => 'Question',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
