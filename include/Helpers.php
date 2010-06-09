<?php

function redirect_if_not_logged_in($location)
{
  if(!User::logged_in()){
    header("location:$location");
  }
}

function redirect_if_not_admin($location)
{
  if(!User::is_admin()){
    header("location:$location");
  }
}

?>
