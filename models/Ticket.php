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

  static function openTickets()
  {
    $tickets = Ticket::all(array('conditions' => 'state = "OPEN"'));
    $tickets = array_reverse($tickets);
    return $tickets;
  }

}
?>
