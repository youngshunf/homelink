<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InterviewRegister;

/**
 * SearchInterviewRegister represents the model behind the search form about `common\models\InterviewRegister`.
 */
class SearchInterviewRegister extends InterviewRegister
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activity_id', 'signup_result', 'interview_result', 'is_appeal', 'created_at', 'updated_at'], 'integer'],
            [['user_guid', 'district_code', 'district_name', 'year_month', 'work_number', 'name', 'mobile', 'remark'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InterviewRegister::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'district_code' => $this->district_code,
            'activity_id' => $this->activity_id,
            'signup_result' => $this->signup_result,
            'interview_result' => $this->interview_result,
            'is_appeal' => $this->is_appeal,
        ]);

        $query->andFilterWhere(['like', 'user_guid', $this->user_guid])
            ->andFilterWhere(['like', 'district_name', $this->district_name])
            ->andFilterWhere(['like', 'year_month', $this->year_month])
            ->andFilterWhere(['like', 'work_number', $this->work_number])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
