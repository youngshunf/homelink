<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "report_target".
 *
 * @property integer $id
 * @property string $name
 * @property double $weight
 * @property integer $pid
 * @property integer $created_at
 */
class ReportTarget extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_target';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight'], 'number'],
            [['pid', 'created_at'], 'integer'],
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
            'name' => '指标名称',
            'weight' => '指标权重',
            'pid' => 'Pid',
            'created_at' => '创建时间',
        ];
    }
    public function getPuser(){
        return $this->hasOne(AdminUser::className(), ['id'=>'pid']);
    }
}
