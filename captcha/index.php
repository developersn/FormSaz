<?php
session_start();
include_once dirname(__FILE__).'/../protected/components/ExtraCache.php';

 //create the random string using the upper function
 //(if you want more than 5 characters just modify the parameter)
 
 $rand_str=mt_rand(10000,99999);
 $rand_str = str_replace('0',6,$rand_str);
 
 if(empty($_SESSION['numcaptcha']) or $_SESSION['numcaptcha']>mt_rand(2,5))
 {
	$_SESSION['rand_str'] = $rand_str;
	$_SESSION['numcaptcha'] = 0;
 }
 
 $rand_str = $_SESSION['rand_str'];
 $_SESSION['numcaptcha']++;
 //$rand_str = (int) date('YmDHi');
 //$_SESSION['capt'] = $rand_str;
 
 $key = ! empty($_GET['key'])? $_GET['key']:'none';
 $key .= $_SERVER['REMOTE_ADDR'];
 ExtraCache::ins()->setPerfix('captcha')->add($key,$rand_str);
 // جدا کردن رشته به صورت 5 کاراکتر
  $letter1=substr($rand_str,0,1);
  $letter2=substr($rand_str,1,1);
  $letter3=substr($rand_str,2,1);
  $letter4=substr($rand_str,3,1);
  $letter5=substr($rand_str,4,1);
 //Creates an image from a png file. If you want to use gif or jpg images,
 //just use the coresponding functions: imagecreatefromjpeg and imagecreatefromgif.
 $image=imagecreatefrompng("img.png");
 //Get a random angle for each letter to be rotated with.
 // تعیین زاویه نمایش کاراکترها
 $angle1 = rand(-20, 20);
 $angle2 = rand(-20, 20);
 $angle3 = rand(-20, 20);
 $angle4 = rand(-20, 20);
 $angle5 = rand(-20, 20);
 //Get a random font. (In this examples, the fonts are located in "fonts" directory and named from 1.ttf to 10.ttf)
 // تعیین فونت برای نمایش هر یک از کاراکترها
 
 $font1 = 'font/1.ttf';
 $font2 = 'font/1.ttf';
 $font3 = 'font/1.ttf';
 $font4 = 'font/1.ttf';
 $font5 = 'font/1.ttf';
 
  
 //Define a table with colors (the values are the RGB components for each color).
  
 //Get a random color for each letter.
 // تعیین رنگ برای هر یک از کاراکتر ها
 
 $color1=rand(0,255);
 $color2=rand(0,255);
 $color3=rand(0,255);
 $color4=rand(0,255);
 $color5=rand(0,255);
 
 //Allocate colors for letters.
 $textColor1 = imagecolorallocate ($image, $color1,10, $color3);
 $textColor2 = imagecolorallocate ($image, $color4,13, $color2);
 $textColor3 = imagecolorallocate ($image, $color2,20, $color1);
 $textColor4 = imagecolorallocate ($image, $color3,10, $color5);
 $textColor5 = imagecolorallocate ($image, $color5,20, $color4);
  
 //Write text to the image using TrueType fonts.
 // تعیین سایز برای هر یک از کاراکترها
 $size1 = rand(17,20);
 $size2 = rand(17,20);
 $size3 = rand(17,20);
 $size4 = rand(17,20);
 $size5 = rand(17,20);
 // تعیین موقعیت نمایش برای هر کدام از کاراکترها
 $y_pos1 = rand(20,28);
 $y_pos2 = rand(20,28);
 $y_pos3 = rand(20,28);
 $y_pos4 = rand(20,28);
 $y_pos5 = rand(20,28);
  
 imagettftext($image, $size1, $angle1, 5, $y_pos1, $textColor1, $font1, $letter1);
 imagettftext($image, $size2, $angle2, 19, $y_pos2, $textColor2, $font2, $letter2);
 imagettftext($image, $size3, $angle3, 40, $y_pos3, $textColor3, $font3, $letter3);
 imagettftext($image, $size4, $angle4, 58, $y_pos4, $textColor4, $font4, $letter4);
 imagettftext($image, $size5, $angle5, 72, $y_pos5, $textColor5, $font5, $letter5);
  

  
 header("Content-type: image/png");
 //Output image to browser
 //imagejpeg($image);
 imagepng($image);
 //Destroys the
 imagedestroy($image);
 
 
 
?> 