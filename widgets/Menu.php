<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\geolocation\widgets;

use Yii;
use yii\helpers\Url;

use humhub\modules\directory\models\User;

/**
 * Directory Menu
 *
 * @since 0.21
 * @author Luke
 */
class Menu extends \humhub\widgets\BaseMenu
{

    public $template = "@humhub/widgets/views/leftNavigation";

    public function init()
    {
        $this->addItemGroup([
            'id' => 'directory',
            'label' => Yii::t('DirectoryModule.base', '<strong>Directory</strong> menu'),
            'sortOrder' => 100,
        ]);

        if (Yii::$app->getModule('directory')->isGroupListingEnabled()) {
            $this->addItem([
                'label' => Yii::t('DirectoryModule.base', 'Groups'),
                'group' => 'directory',
                'url' => Url::to(['/geolocation/index/groups']),
                'sortOrder' => 100,
                'isActive' => (Yii::$app->controller->action->id == "groups"),
            ]);
        }

        $this->addItem([
            'label' => Yii::t('DirectoryModule.base', 'Members'),
            'group' => 'directory',
            'url' => Url::to(['/geolocation/index/members']),
            'sortOrder' => 200,
            'isActive' => (Yii::$app->controller->action->id == "members"),
        ]);

        $this->addItem([
            'label' => Yii::t('DirectoryModule.base', 'Spaces'),
            'group' => 'directory',
            'url' => Url::to(['/geolocation/index/spaces']),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->action->id == "spaces"),
        ]);

        $this->addItem([
            'label' => Yii::t('DirectoryModule.base', 'User profile posts'),
            'group' => 'directory',
            'url' => Url::to(['/geolocation/index/user-posts']),
            'sortOrder' => 400,
            'isActive' => (Yii::$app->controller->action->id == "user-posts"),
        ]);

        parent::init();
    }

}

?>
