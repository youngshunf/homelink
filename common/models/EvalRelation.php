<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "eval_relation".
 *
 * @property integer $id
 * @property string $work_number
 * @property string $up_work_number
 * @property string $created_at
 */
class EvalRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eval_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['work_number', 'up_work_number'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_number' => '工号',
            'up_work_number' => '上级工号',
            'created_at' => 'Created At',
        ];
    }
    public function getUser(){
        return $this->hasOne(AuthUser::className(), ['work_number'=>'work_number']);
    }
}
