<?php

use humhub\modules\geolocation\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;

return [
	'id' => 'geolocation',
	'class' => 'humhub\modules\geolocation\Module',
	'namespace' => 'humhub\modules\geolocation',
	'events' => [

		[
			'class' => AdminMenu::class,
			'event' => AdminMenu::EVENT_INIT,
			'callback' => [Events::class, 'onAdminMenuInit']
		],

        ['class' => TopMenu::class, 'event' => TopMenu::EVENT_BEFORE_RUN, 'callback' => [\humhub\modules\geolocation\Events::class, 'onTopMenuInit']],
	],


];
