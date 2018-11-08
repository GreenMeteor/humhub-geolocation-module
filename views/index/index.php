<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\conerd\humhub\modules\geolocation\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('GeolocationModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("geolocation", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('GeolocationModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>Geolocation</strong> <?= Yii::t('GeolocationModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('GeolocationModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('GeolocationModule.base', 'Say Hello!'))->action("geolocation.hello")->loader(false); ?></div>
