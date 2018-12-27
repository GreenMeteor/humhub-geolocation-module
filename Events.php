<?php

namespace  humhub\modules\geolocation;

use humhub\components\Event;
use humhub\widgets\TopMenu;
use Yii;
use yii\helpers\Url;

class Events extends \yii\base\BaseObject
{
    public static function directoryAfterInit(){
        $x = 3;
    }

    /**
     * On build of the TopMenu, check if module is enabled
     * When enabled add a menu item
     *
     * @param $event \yii\base\Event
     */
    public static function onTopMenuInit($event)
    {
        $arrayNumber = null;
        $it = 0;
        /* @var $sender TopMenu */
        foreach ($event->sender->items as $item){
            if ($item['label'] == Yii::t('DirectoryModule.base', 'Directory'))
            {
                $arrayNumber = $it;
            }
            $it++;
        }
        $event->sender->items[$arrayNumber]['url'] = Url::to([ "/geolocation/index"]);
    }


    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit(\yii\base\Event $event)
    {
        $event->sender->addItem([
            'label' => Yii::t('GeolocationModule.base', 'Geolocation'),
            'url' => Url::toRoute('/geolocation/admin/index'),
            'group' => 'settings',
            'icon' => '<i class="fa fa-compass"></i>',
            'isActive' => Yii::$app->controller->module && Yii::$app->controller->module->id == 'geolocation' && Yii::$app->controller->id == 'admin',
            'sortOrder' => 650
        ]);
    }
}
