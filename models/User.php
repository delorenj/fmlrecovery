<?php
class User extends ActiveRecord\Model
{
  static $has_many = array(
    array('Addresses'),
    array('Tickets')
  );
  
	static $validates_presence_of = array(
		array('first_name'),
		array('last_name'),
		array('email'),
		array('crypted_password')
	);

	static $validates_uniqueness_of = array(
		array('email', 'message' => 'Email already exists' )
	);

  static function logged_in()
  {
    if(isset($_SESSION['userid']))
      return true;
    else
      return false;
  }

  static function openTickets()
  {
    $tickets = User::current_user()->tickets;
    $opentix = array();
    fb("num of tickets: ".count($tickets));
    foreach($tickets as $k => $t){
      if($t->status != "Complete"){
        $opentix[] = $t;
      }
    }
    return $opentix;
  }

  function current_user()
  {
	if(User::logged_in())
    return User::find_by_id($_SESSION['userid']);
   else
    return null;
  }

}
?>
