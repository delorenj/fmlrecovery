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
  
  static $validates_presence_of = array(
    array('streetlines'),
    array('city'),
    array('stateorprovincecode'),
    array('postalcode'),
    array('phonenumber')
	);

  static $validates_size_of = array(
    array('phonenumber', 'is' => 10, 'wrong_length' => 'You must enter a valid phone number!'),
    array('stateorprovincecode', 'is' => 2, 'wrong_length' => 'You must enter the abbreviated state!'),
    array('postalcode', 'is' => 5, 'wrong_length' => 'You must enter a valid 5-digit zip code!')
  );
   
}
?>
