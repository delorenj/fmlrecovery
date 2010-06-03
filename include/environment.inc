<?
require_once('FirePHPCore/fb.php');

require_once 'php-activerecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
            $cfg->set_model_directory('models');
            $cfg->set_connections(array('development' => 'mysql://epc:Iree3cam@localhost/epc'));
        });

require_once 'include/Helpers.php';
?>
