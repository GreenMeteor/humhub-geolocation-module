<?php

namespace humhub\modules\geolocation\migration;

use yii\db\Migration;
use Yii;

class Uninstall extends Migration
{

    public function up()
    {
        $table = Yii::$app->db->getTableSchema('user');

        if (isset($table->columns['longitude'])){
            $this->dropColumn('user', 'longitude');
        }

        if (isset($table->columns['latitude'])){
            $this->dropColumn('user', 'latitude');
        }

        $this->db->createCommand('DROP FUNCTION IF EXISTS `get_distance_in_miles_between_geo_locations`')->execute();
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
