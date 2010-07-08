<?php

class EmailLabelException extends Exception {
  public function errorMessage()
  {
    $errorMsg = "There was an error creating an email label: <b>".$this->getLine().":".$this->getMessage();
    return $errorMsg;
  }
}
?>
