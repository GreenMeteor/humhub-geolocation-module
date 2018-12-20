<?php

namespace  humhub\modules\geolocation;

use humhub\components\Event;
use humhub\widgets\TopMenu;
use Yii;
use yii\helpers\Url;

class Events
{
    public static function directoryAfterInit(){
        $x = 3;
    }
    /**
     * Defines what to do when the top menu is initialized.
     *
     * @param $event \yii\base\Event
     */
    public static function onTopMenuInit($event)
    {
        $arrayNumber = null;
        $it = 0;
        /* @var $sender TopMenu */
        foreach ($event->sender->items as $item){
            if ($item['label'] == Yii::t('GeolocationModule.base', 'Directory')){
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
    public static function onAdminMenuInit($event)
    {

        $event->sender->addItem([
            'label' => Yii::t('GeolocationModule.base', 'Geolocation'),
            'url' => Url::to(['/geolocation/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-compass"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'geolocation' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99999,
        ]);
    }
}
