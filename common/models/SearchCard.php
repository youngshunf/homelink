<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Card;

/**
 * SearchCard represents the model behind the search form about `common\models\Card`.
 */
class SearchCard extends Card
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_id', 'created_at', 'updated_at'], 'integer'],
            [['user_guid', 'name', 'mobile', 'email', 'district', 'shop', 'business_circle', 'building', 'address', 'sign', 'path', 'photo', 'template'], 'safe'],
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
        $query = Card::find()->orderBy("score desc");

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
            'card_id' => $this->card_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user_guid', $this->user_guid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'shop', $this->shop])
            ->andFilterWhere(['like', 'business_circle', $this->business_circle])
            ->andFilterWhere(['like', 'building', $this->building])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'sign', $this->sign])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'template', $this->template]);

        return $dataProvider;
    }
}
