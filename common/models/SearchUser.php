<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * SearchUser represents the model behind the search form about `common\models\User`.
 */
class SearchUser extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role_id', 'sex', 'is_auth', 'mobile_verify', 'email_verify', 'isenable', 'created_at', 'updated_at'], 'integer'],
            [['openid', 'user_guid', 'username', 'access_token', 'auth_key', 'password', 'real_name', 'work_number', 'nick', 'city', 'province', 'country', 'address', 'big_district', 'business_district', 'path', 'photo', 'img_path', 'mobile', 'email', 'district', 'business_circle', 'building', 'shop', 'sign', 'subscribe_time'], 'safe'],
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
        $query = User::find()->orderBy('created_at desc');

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

        $query->andFilterWhere([         
            'is_auth' => $this->is_auth,
            'mobile_verify' => $this->mobile_verify,
            'email_verify' => $this->email_verify,
            'isenable' => $this->isenable,
            'subscribe_time' => $this->subscribe_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'user_guid', $this->user_guid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'work_number', $this->work_number])
            ->andFilterWhere(['like', 'nick', $this->nick])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'big_district', $this->big_district])
            ->andFilterWhere(['like', 'business_district', $this->business_district])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'img_path', $this->img_path])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'business_circle', $this->business_circle])
            ->andFilterWhere(['like', 'building', $this->building])
            ->andFilterWhere(['like', 'shop', $this->shop])
            ->andFilterWhere(['like', 'sign', $this->sign]);

        return $dataProvider;
    }
}
