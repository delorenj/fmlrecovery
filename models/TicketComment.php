<?php
class TicketComment extends ActiveRecord\Model
{
  static $belongs_to = array(
    array('Ticket')
  );

}
?>
