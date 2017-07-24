<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Vote;

/**
 * SearchVote represents the model behind the search form about `common\models\Vote`.
 */
class SearchVote extends Vote
{
    public $status=0;
    
    const GOING=1;
    const PASSED=2;
    const FURTURE=3;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vote_id', 'start_time', 'end_time', 'vote_number', 'created_at'], 'integer'],
            [['title', 'content'], 'safe'],
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
        $query = Vote::find();

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
       
        $now=time();
        if($this->status==SearchVote::GOING){
            $query->andWhere(" $now>start_time and $now<end_time ");
        }elseif($this->status==SearchVote::PASSED){
            $query->andWhere(" $now>end_time ");
        }elseif ($this->status==SearchVote::FURTURE){
            $query->andWhere(" $now<start_time ");
        }

        $query->andFilterWhere([      
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'vote_number' => $this->vote_number,
        
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->orderBy('created_at desc');

        return $dataProvider;
    }
}
