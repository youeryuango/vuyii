<?php

namespace common\models\user\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\UserAdmin;

/**
 * UserAdminSearch represents the model behind the search form of `common\models\user\UserAdmin`.
 */
class UserAdminSearch extends UserAdmin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'email', 'status', 'create_time'], 'safe'],
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
        $query = UserAdmin::find()->asArray()->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => isset($params['pageSize']) && !empty($params['pageSize']) ? $params['pageSize'] : 20,
            ],
        ]);
        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
//            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        if ($this->create_time) {
			list($start, $end) = explode(' - ', $this->create_time);
			$query->andFilterWhere(['between', 'create_time', $start, $end]);
		};
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
