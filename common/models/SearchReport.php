<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Report;

/**
 * SearchReport represents the model behind the search form about `common\models\Report`.
 */
class SearchReport extends Report
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'start_time', 'end_time', 'report_time', 'created_at', 'updated_at'], 'integer'],
            [['name', 'desc'], 'safe'],
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
        $query = Report::find();

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
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'report_time' => $this->report_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
