<?php
  define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/Nilkhet/');
  //here we will define cookies for cart and we will set time on it
  define('CART_COOKIE','RandomStringSET123');//cookie can be set in random string and it will remail constant for the browser
  define('CART_COOKIE_EXPIRE',time()+(86400*30));//cookie gets time expiration in seconds so we will grab the current time and add necessary seconds to last for 30 days

  define ('DELIVERY_CHARGE',50.00);
  //$_SERVER['DOCUMENT_ROOT'] gives out the root directory
  //echo BASEURL;
  //Here we will see the base path of our project

?>