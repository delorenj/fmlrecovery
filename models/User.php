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
}
?>
