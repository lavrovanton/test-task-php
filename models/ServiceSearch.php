<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class ServiceSearch extends Service
{
    public function rules()
    {
        return [
            [['type'], 'string', 'max' => 255],
            [['client_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Service::find()->joinWith(['client']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'type',
                'full_name' => [
                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                    'label' => 'Full Name',
                ],
                'ip',
                'domain'
            ],
            'defaultOrder' => ['id' => SORT_ASC]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['=', 'type', $this->type])
              ->andFilterWhere(['=', 'client_id', $this->client_id]);

        return $dataProvider;
    }
}