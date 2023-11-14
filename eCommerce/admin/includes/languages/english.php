<?php
function lang($phrase)
{
  static $lang = array(
  'message' => 'مرحباً بك'
  );

  return $lang[$phrase];
}
;