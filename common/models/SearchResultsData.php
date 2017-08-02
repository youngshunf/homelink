<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ResultsData;

/**
 * SearchResultsData represents the model behind the search form about `common\models\ResultsData`.
 */
class SearchResultsData extends ResultsData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          
            [[ 'work_number', 'youmi','big_district','business_district','name'], 'safe'],
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
        $query = ResultsData::find()->orderBy('created_at desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'work_number', $this->work_number])
        ->andFilterWhere(['like', 'big_district', $this->big_district])
        ->andFilterWhere(['like', 'business_district', $this->business_district])
        ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'youmi', $this->youmi]);

        return $dataProvider;
    }
}
