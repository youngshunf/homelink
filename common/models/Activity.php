<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property integer $activity_id
 * @property string $title
 * @property string $content
 * @property string $path
 * @property string $photo
 * @property integer $scope
 * @property integer $type
 * @property string $start_time
 * @property string $end_time
 * @property string $province
 * @property string $city
 * @property string $address
 * @property integer $max_number
 * @property string $sign_end_time
 * @property string $shop
 * @property string $created_at
 * @property string $updated_at
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scope' ,'type', 'max_number','title','province','city','address','start_time','end_time','sign_end_time','sign_start_time'], 'required'],
            [[ 'scope', 'type', 'max_number', 'created_at', 'updated_at','is_top','score','pid'], 'integer'],
            [['content'], 'string'],
            [['outer_link','is_card_done','shop'],'safe'],
            [['title'], 'string', 'max' => 256],
            [['path', 'photo'], 'string', 'max' => 128],
            [['province', 'city','signup_deny_template','signup_pass_template','result_deny_template','result_pass_template'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 258],
            [['shop'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => 'Activity ID',
            'title' => '标题',
            'content' => '活动介绍',
            'path' => '路径',
            'photo' => '封面图片',
            'scope' => '活动参与对象',
            'type' => '活动类型',
            'start_time' => '活动开始时间',
            'end_time' => '活动结束时间',
            'province' => '省份',
            'city' => '城市',
            'address' => '活动地址',
            'max_number' => '最大参与人数',
            'sign_end_time' => '报名截止时间',
            'sign_start_time' => '报名开始时间',
            'score'=>'学分',
            'shop' => '竞聘店面',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
