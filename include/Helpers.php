<?php

function redirect_if_not_logged_in($location)
{
  if(!User::logged_in()){
    header("location:$location");
  }
}

?>
