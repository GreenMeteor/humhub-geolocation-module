<?php

namespace humhub\modules\geolocation\models;

use humhub\components\ActiveRecord;
use humhub\modules\comment\widgets\Comments;
use humhub\modules\content\models\Content;
use humhub\modules\file\models\File;
use humhub\modules\user\models\User;
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\content\components\ContentContainerActiveRecord;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $longitude
 * @property string $latitude
 *
 */
class User extends User
{
    public $distance;

}
