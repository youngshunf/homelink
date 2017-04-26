<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property integer $aid
 * @property integer $qid
 * @property integer $oid
 * @property string $answer
 * @property string $created_at
 * @property string $updated_at
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        
            [['answer'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aid' => 'Aid',
            'qid' => 'Qid',
            'oid' => 'Oid',
            'answer' => '答案',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
