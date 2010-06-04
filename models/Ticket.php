<?php
class Ticket extends ActiveRecord\Model
{
  static $belongs_to = array(
    array('User'),
    array('Service')
  );

}
?>
