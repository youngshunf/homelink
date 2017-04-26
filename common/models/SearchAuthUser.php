<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchCard represents the model behind the search form about `common\models\Card`.
 */
class SearchAuthUser extends AuthUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'name',  'big_district', 'shop'], 'safe'],
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
        $query = AuthUser::find()->orderBy("created_at desc");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'big_district', $this->big_district])
            ->andFilterWhere(['like', 'shop', $this->shop]);

        return $dataProvider;
    }
}
