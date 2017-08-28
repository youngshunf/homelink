<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ReportResult;

/**
 * SearchReportResult represents the model behind the search form about `common\models\ReportResult`.
 */
class SearchReportResult extends ReportResult
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'reportid', 'report_time', 'created_at', 'updated_at','pid'], 'integer'],
            [['user_guid', 'work_number', 'name', 'desc', 'path', 'photo'], 'safe'],
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
        $query = ReportResult::find()->orderBy('created_at desc');

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
            'pid' => $this->pid,
            'reportid' => $this->reportid,
            'report_time' => $this->report_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user_guid', $this->user_guid])
            ->andFilterWhere(['like', 'work_number', $this->work_number])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
