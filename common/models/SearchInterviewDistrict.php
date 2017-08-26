<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InterviewDistrict;

/**
 * SearchInterviewDistrict represents the model behind the search form about `common\models\InterviewDistrict`.
 */
class SearchInterviewDistrict extends InterviewDistrict
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['district_code', 'district_name', 'assistant_number', 'assistant_name', 'supervisor_number', 'supervisor_name'], 'safe'],
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
        $query = InterviewDistrict::find();

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
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'district_code', $this->district_code])
            ->andFilterWhere(['like', 'district_name', $this->district_name])
            ->andFilterWhere(['like', 'assistant_number', $this->assistant_number])
            ->andFilterWhere(['like', 'assistant_name', $this->assistant_name])
            ->andFilterWhere(['like', 'supervisor_number', $this->supervisor_number])
            ->andFilterWhere(['like', 'supervisor_name', $this->supervisor_name]);

        return $dataProvider;
    }
}
