<?php

namespace  humhub\modules\geolocation\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
* AssetsBundles are used to include assets as javascript or css files
*/
class Assets extends AssetBundle
{
    /**
     * @var string defines the path of your module assets
     */
    public $sourcePath = '@geolocation/resources';

    /**
     * @var array defines where the js files are included into the page, note your custom js files should be included after the core files (which are included in head)
     */
    public $jsOptions = ['position' => View::POS_END];

    /**
     * @var array change forceCopy to true when testing your js in order to rebuild this assets on every request (otherwise they will be cached)
     */
    public $publishOptions = [
        'forceCopy' => false
    ];

    public $js = [
        'js/humhub.geolocation.js'
    ];

}
