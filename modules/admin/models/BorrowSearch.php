<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Borrow;

/**
 * BorrowSearch represents the model behind the search form of `app\models\Borrow`.
 */
class BorrowSearch extends Borrow
{
    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['book.name', 'client.name']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'taken_time', 'brought_time', 'status'], 'integer'],
            [['book.name', 'client.name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Borrow::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['book', 'client']);
        $dataProvider->sort->attributes['book.name'] = [
            'asc' => ['book.name' => SORT_ASC],
            'desc' => ['book.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['client.name'] = [
            'asc' => ['client.name' => SORT_ASC],
            'desc' => ['client.name' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'taken_time' => $this->taken_time,
            'brought_time' => $this->brought_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'book.name', $this->getAttribute('book.name')]);
        $query->andFilterWhere(['like', 'client.name', $this->getAttribute('client.name')]);

        return $dataProvider;
    }
}
