<?php
class Service extends ActiveRecord\Model
{
  static $has_many = array(
    array('Tickets')
  );

}
?>
