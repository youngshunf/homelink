<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Activity;

/**
 * SearchActivity represents the model behind the search form about `common\models\Activity`.
 */
class SearchActivity extends Activity
{
    public $typeFlag=0;
    public $pFlag;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id',  'type','pid','pFlag' ,'score', 'max_number'], 'safe'],
            [['title', 'content', 'path', 'photo', 'province', 'city', 'address', 'shop','is_top','scope',], 'safe'],
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
        $query = Activity::find()->orderBy("is_top desc,start_time desc");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
               'pagesize'=>10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }
        
        if($this->typeFlag==0){
            $query->andWhere(" type=0 or type=2 ");
        }elseif($this->typeFlag==1){
            $query->andWhere(" type=1 or type=3");
        }
        $user=yii::$app->user->identity;
        if($user->role_id==98){
            $query->andWhere(['pid'=>$user->id]);
        }
        if($user->role_id<=10){
            $query->andWhere("pid=0 or pid=".$user->id);
        }

        $query->andFilterWhere([
            'activity_id' => $this->activity_id,
            'is_top'=>$this->is_top,
            'score'=>$this->score,
            'scope' => $this->scope,
            'type' => $this->type,
            'max_number' => $this->max_number,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'shop', $this->shop]);

        return $dataProvider;
    }
}
