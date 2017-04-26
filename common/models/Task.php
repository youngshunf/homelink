<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $name
 * @property string $standard
 * @property string $requirement
 * @property integer $score
 * @property string $path
 * @property string $photo
 * @property string $created_at
 * @property string $updated_at
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','standard','score'], 'required'],
            [['requirement'], 'string'],
            [['score', 'created_at', 'updated_at'], 'integer'],
            [['name', 'standard'], 'string', 'max' => 255],
            [['path', 'photo'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '任务名称',
            'standard' => '完成标准',
            'requirement' => '任务要求',
            'score' => '分值',
            'count_exec'=>'做任务人数',
            'path' => 'Path',
            'photo' => 'Photo',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
