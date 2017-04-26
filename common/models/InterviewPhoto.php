<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_photo".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $work_number
 * @property string $district_code
 * @property string $district_name
 * @property string $path
 * @property string $photo
 * @property string $year_month
 * @property string $photo1
 * @property string $path1
 * @property integer $created_at
 * @property integer $updated_at
 */
class InterviewPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview_photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['user_guid'], 'string', 'max' => 64],
            [['work_number', 'district_code', 'district_name', 'year_month'], 'string', 'max' => 20],
            [['path', 'photo'], 'string', 'max' => 255],
            [['photo1', 'path1'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_guid' => 'User Guid',
            'work_number' => '上传者工号',
            'name' => '上传者姓名',
            'district_code' => '大区编号',
            'district_name' => '大区名',
            'path' => 'Path',
            'photo' => 'Photo',
            'year_month' => '月份',
            'photo1' => 'Photo1',
            'path1' => 'Path1',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
