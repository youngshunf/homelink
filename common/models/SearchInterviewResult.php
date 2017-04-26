<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InterviewResult;

/**
 * SearchInterviewResult represents the model behind the search form about `common\models\InterviewResult`.
 */
class SearchInterviewResult extends InterviewResult
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['name', 'id_code', 'level', 'rec_work_number', 'rec_name', 'interview_time', 'interview_result', 'train_result', 'status', 'remark'], 'safe'],
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
        $query = InterviewResult::find();

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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'id_code', $this->id_code])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'rec_work_number', $this->rec_work_number])
            ->andFilterWhere(['like', 'rec_name', $this->rec_name])
            ->andFilterWhere(['like', 'interview_time', $this->interview_time])
            ->andFilterWhere(['like', 'interview_result', $this->interview_result])
            ->andFilterWhere(['like', 'train_result', $this->train_result])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
