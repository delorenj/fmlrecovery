<?php
class Address extends ActiveRecord\Model
{
  static $belongs_to = array(
    array('User')
  );

  /*
	static $validates_presence_of = array(
		array('user_id'),
		array('nickname'),
		array('streetLines'),
		array('city'),
    array('stateOrProvinceCode'),
    array('postalCode'),
    array('phoneNumber')
	);
   * 
   */
}
?>
