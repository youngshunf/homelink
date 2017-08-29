<?php

namespace common\models;

use Yii;
use backend\models\AdminUser;

/**
 * This is the model class for table "report_level".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $up
 * @property integer $down
 * @property integer $same
 * @property integer $self
 */
class ReportLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'up', 'down', 'same', 'self'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'up' => '上级权重',
            'down' => '下级权重',
            'same' => '同级权重',
            'self' => '自评权重',
        ];
    }
    
    public function getPuser(){
        return $this->hasOne(AdminUser::className(), ['id'=>'pid']);
    }
}
