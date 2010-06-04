<?php
class Address extends ActiveRecord\Model
{
  static $before_save = array("upcase_state");

  static $belongs_to = array(
    array('User')
  );

  public function upcase_state() {
    $this->stateorprovincecode = strtoupper($this->stateorprovincecode);
  }
  
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
