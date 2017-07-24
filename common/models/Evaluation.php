<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "evaluation".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $eval_user
 * @property integer $qid
 * @property string $created_at
 * @property string $updated_at
 */
class Evaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'evaluation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',        
            'qid' => 'Qid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function  getUser(){
        return $this->hasOne(AuthUser::className(), ['work_number'=>'work_number']);
    }
    
    public function getEvalUser(){
        return $this->hasOne(AuthUser::className(), ['work_number'=>'eval_work_number']);
    }
    
    public function getQuestion(){
        return $this->hasOne(Question::className(), ['qid'=>'qid']);
    }
}
