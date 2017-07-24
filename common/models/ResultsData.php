<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "results_data".
 *
 * @property integer $id
 * @property string $work_number
 * @property string $year_month
 * @property string $name
 * @property string $big_district
 * @property string $business_district
 * @property double $honor_score
 * @property double $co_index
 * @property double $teach_score
 * @property double $results
 * @property string $youmi
 * @property integer $rank
 * @property double $total_score
 * @property string $created_at
 */
class ResultsData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'results_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//             [['honor_score', 'co_index', 'teach_score', 'results', 'total_score'], 'number'],
//             [['rank', 'created_at'], 'integer'],
            [['work_number'], 'string', 'max' => 32],
            [['year_month'], 'string', 'max' => 12],
            [['name', 'big_district', 'business_district'], 'string', 'max' => 20],
            [['youmi'], 'string', 'max' => 255]
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
            'year_month' => '月份',
            'name' => '姓名',
            'big_district' => '运营管理大区',
            'business_district' => '业务区域',
            'line_pr'=>'边数pr',
            'shop'=>'门店',
            'rank' => '个人业绩大区排名',
            'total_score' => '作战小组业绩大区排名',
            'results' => '状态',
            'teach_score' => '总学分',
            'co_index' => '任务卡学分',
            'honor_score' => '课程学分',
            'youmi' => '信誉积分换算学分',
            'remark'=>'备注',
            'created_at' => '导入时间',
        ];
    }
    
    public function getUser(){
        return $this->hasOne(AuthUser::className(), ['work_number'=>'work_number']);
    }
}
