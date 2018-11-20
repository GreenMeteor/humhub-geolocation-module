<?php

use humhub\modules\geolocation\widgets\Menu;
use humhub\widgets\FooterMenu;
use humhub\modules\directory\widgets\Sidebar;

\humhub\assets\JqueryKnobAsset::register($this);
\humhub\modules\geolocation\assets\Assets::register($this);
?>

<div class="container">
    <div class="row">
        <div class="col-md-2">
            <?= Menu::widget(); ?>
        </div>
        <div class="col-md-7">
            <?= $content; ?>
        </div>
        <div class="col-md-3">
            <?= Sidebar::widget(); ?>
            <?= FooterMenu::widget(['location' => FooterMenu::LOCATION_SIDEBAR]); ?>
        </div>
    </div>
</div>
