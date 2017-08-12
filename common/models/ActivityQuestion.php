<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_question".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $template
 * @property integer $created_at
 * @property integer $updated_at
 */
class ActivityQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'activity_id', 'created_at', 'updated_at'], 'integer'],
            [['template'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'template' => 'Template',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
