<?php
   error_reporting(E_ALL ^E_WARNING ^E_NOTICE); 

   $data = array();
   
   $data['id'] = '38675779';
   $data['domain'] = 'домен вашего сервера';
   $data['timeEnd'] = 1424368601;
   
   $secretKey = 'kc21uc29y38xy78y*@G#UCHNUYT#x0^T^&!^&#2fmj83e'; 
   
   $data = @serialize($data);
   mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
   $iv = $iv_size = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $encryptData = mcrypt_encrypt(MCRYPT_BLOWFISH, $secretKey, $data, MCRYPT_MODE_ECB, $iv);

   file_put_contents('license', $encryptData);
   
   echo 'OK';
?>