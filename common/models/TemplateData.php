<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template_data".
 *
 * @property integer $id
 * @property string $template_id
 * @property string $key
 * @property string $value
 * @property string $created_at
 */
class TemplateData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
   
            [['created_at'], 'integer'],
            [['template_id', 'key'], 'string', 'max' => 128],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_id' => 'Template ID',
            'key' => 'Key',
            'value' => 'Value',
            'created_at' => 'Created At',
        ];
    }
}
