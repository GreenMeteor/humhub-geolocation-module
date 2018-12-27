<?php


namespace humhub\modules\geolocation\migration;

use yii\db\Migration;
use yii;

/**
 * Class enable
 */
class Enable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('user');

        if (!isset($table->columns['longitude'])) {
            $this->addColumn('user', 'longitude', 'string');
        }

        if (!isset($table->columns['latitude'])) {
            $this->addColumn('user', 'latitude', 'string');
        }
        $this->db->createCommand('DROP FUNCTION IF EXISTS `get_distance_in_miles_between_geo_locations`')->execute();
        $this->db->createCommand("CREATE FUNCTION get_distance_in_miles_between_geo_locations(geo1_latitude decimal(10,6), geo1_longitude decimal(10,6), geo2_latitude decimal(10,6), geo2_longitude decimal(10,6)) \n"
            . "            RETURNS decimal(10,3) DETERMINISTIC \n"
            . "            BEGIN \n"
            . "            return ((ACOS(SIN(geo1_latitude * PI() / 180) * SIN(geo2_latitude * PI() / 180) + COS(geo1_latitude * PI() / 180) * COS(geo2_latitude * PI() / 180) * COS((geo1_longitude - geo2_longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515); \n"
            . "            END")->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "enable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "enable cannot be reverted.\n";

        return false;
    }
    */
}
