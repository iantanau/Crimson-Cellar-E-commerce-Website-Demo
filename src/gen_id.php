<?php

function gen_id($length) {
  $id = "";
  $chars = "012345678936792572378883256937";
  for($i = 0; $i < $length; $i++) {
    $x = rand(0, strlen($chars) -1);
    $id.= $chars[$x];
  }
  return $id.time();
}

?>
