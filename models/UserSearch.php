<?php

namespace conerd\humhub\modules\geolocation\models;

use conerd\humhub\modules\geolocation\Module;
use humhub\modules\queue\driver\MySQLCommand;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use conerd\humhub\modules\geolocation\models\User;
use yii\db\QueryBuilder;

/**
 * UserSearch represents the model behind the search form of `conerd\humhub\modules\geolocation\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_by', 'visibility', 'contentcontainer_id'], 'integer'],
            [['guid', 'username', 'email', 'auth_mode', 'tags', 'language', 'created_at', 'updated_at', 'last_login', 'time_zone', 'authclient_id', 'longitude', 'latitude'], 'safe'],
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
        $query = User::find();

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

        $newQuery = new MySQLCommand('geolocatioin',Module::getInstance());

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'last_login' => $this->last_login,
            'visibility' => $this->visibility,
            'contentcontainer_id' => $this->contentcontainer_id,
        ]);

        $query->andFilterWhere(['like', 'guid', $this->guid])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_mode', $this->auth_mode])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'time_zone', $this->time_zone])
            ->andFilterWhere(['like', 'authclient_id', $this->authclient_id])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'latitude', $this->latitude]);

        return $dataProvider;
    }
}
