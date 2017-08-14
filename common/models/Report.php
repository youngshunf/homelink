<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report".
 *
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $report_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class Report extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['start_time','end_time', 'report_time',], 'safe'],
            [[  'created_at', 'updated_at'], 'integer'],
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
            'name' => '测评标题',
            'desc' => '测评描述',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'report_time' => '出报告时间',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
