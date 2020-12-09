<?php

namespace common\models\post\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\post\Post;

/**
 * PostSearch represents the model behind the search form of `common\models\post\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'base_view_num', 'actual_view_num', 'user_id'], 'integer'],
            [['title', 'desc', 'keywords', 'content', 'status', 'create_time'], 'safe'],
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
        $query = Post::find()->asArray()->orderBy('id DESC');

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
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'base_view_num' => $this->base_view_num,
            'actual_view_num' => $this->actual_view_num,
            'user_id' => $this->user_id,
        ]);

        if ($this->create_time) {
			list($start, $end) = explode(' - ', $this->create_time);
			$query->andFilterWhere(['between', 'create_time', $start, $end]);
		};

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
