<?php

namespace common\models\sys\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sys\SysRole as SysRoleModel;

/**
 * SysRole represents the model behind the search form of `common\models\sys\SysRole`.
 */
class SysRole extends SysRoleModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'sort', 'tips'], 'safe'],
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
        $query = SysRoleModel::find()->asArray()->orderBy('id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : 20,
            ],
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
