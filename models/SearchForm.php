<?php

namespace humhub\modules\geolocation\models;

use humhub\components\ActiveRecord;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\content\models\Content;
use humhub\modules\file\models\File;
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\content\components\ContentContainerActiveRecord;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 */
class SearchForm extends ActiveRecord
{
    public $page;
    public $distance;
    public $groupID;
    public $longitude;
    public $latitude;
    public $keyword;
    public $sex;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page', 'distance', 'groupID'], 'integer'],
            [['longitude', 'latitude'], 'string', 'max' => 45],
            [['keyword'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page' => Yii::t('GeolocationModule.base', 'Page Number'),
            'distance' => Yii::t('GeolocationModule.base', 'Search Distance'),
            'longitude' => Yii::t('GeolocationModule.base', 'Longitude'),
            'latitude' => Yii::t('GeolocationModule.base', 'Latitude'),
            'keyword' => Yii::t('GeolocationModule.base', 'Keyword'),
            'groupID' => Yii::t('GeolocationModule.base', 'Group ID'),
            'sex' => Yii::t('GeolocationModule.base', 'Gender')
        ];
    }

}
