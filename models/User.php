<?php
class User extends ActiveRecord\Model
{
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

	function current_user()
	{
		if(User::logged_in())
			return User::find_by_id($_SESSION['userid']);
		else
			return null;
	}

}
?>
