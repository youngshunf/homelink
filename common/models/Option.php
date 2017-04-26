<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "option".
 *
 * @property integer $oid
 * @property integer $qid
 * @property string $title
 * @property integer $type
 * @property string $created_at
 * @property string $updated_at
 */
class Option extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qid', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'oid' => 'Oid',
            'qid' => 'Qid',
            'title' => '选项',
            'type' => '题目类型',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
