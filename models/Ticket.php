<?php
class Ticket extends ActiveRecord\Model
{
  static $belongs_to = array(
    array('User'),
    array('Service')
  );

  static $has_many = array(
    array('Ticket_Comments')
  );

}
?>
