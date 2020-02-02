<?php

function get_buttons() 
{
  $str='';
  $btns=array(
      2=>'About',
  );

  while(list($k, $v)=each($btns)) 
  {
      $str.='&nbsp; <input type="submit" value="'.$v.'" name="btn_'.$k.'" id=btn_'.$k.'"/>';
  }
  return $str;
}

/**
 * A LENYOMOTT BILLENTYŰT VIZSGÁLSA
 */
if(isset($_POST['btn_2'])) 
{
    echo "about clicked";
}

?>