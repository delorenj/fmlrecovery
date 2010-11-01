<?
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    dirname(__FILE__),
    dirname(__FILE__)."/../"
)));

//require_once('FirePHPCore/fb.php');
function fb($message) {
	error_log($message);
}

require_once 'php-activerecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
            $cfg->set_model_directory(dirname(__FILE__).'/../models');
            $cfg->set_connections(array('development' => 'mysql://fmladmin:Mog43yo...@localhost/fmladmin_epc'));
        });

require_once 'fmlExceptions.php';
require_once 'Helpers.php';
?>
