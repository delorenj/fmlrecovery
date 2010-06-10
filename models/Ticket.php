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

  static function openTickets($uId = null)
  {
    if($uId == null){
      $tickets = Ticket::all(array('conditions' => 'state = "OPEN"'));
    }
    else {
      $tickets = Ticket::all(array('conditions' => array('state = "OPEN" AND user_id = ?', $uId)));
    }
    $tickets = array_reverse($tickets);
    return $tickets;
  }

}
?>
