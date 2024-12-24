<?php

use Illuminate\Support\Facades\Crypt;

function profile(){

   return session()->get('profile');
}

//pass có viết hoa, thường, số, ký tự đặc biệt ít nhất 8 ký tự
function check_password($password){
   if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
      return true;
  } else {
      return false;
  }

}
function convert_price(string $price = '' , bool $title = true) {
    return ($title == false) ? str_replace('.','',$price) : number_format($price,0,',','.');
 }
// demo khó
function check_password_v2($password){
   if($password){
   $check = str_split($password);
   if(count($check)){
       $regexString = 'abcdefghlmnopqrstuvwxyz@$!%*?&';
       $regexNumber = '0123456789';
       foreach($check as $key => $item){
           $check1 = str_contains($regexString,Str::lower($check[$key])) ? Str::lower($check[$key]) : (int)$check[$key];
           $check2 = str_contains($regexString,Str::lower($check[$key + 1]))? Str::lower($check[$key + 1]): (int)$check[$key + 1];
           $check3 = str_contains($regexString,Str::lower($check[$key + 2]))? Str::lower($check[$key + 2]): (int)$check[$key + 2];

           if(!empty($check1) && !empty($check2) && !empty($check3)){

               // $data[] = [
               //     $check1,
               //     $check2,
               //     $check3
               // ];
                // trường hợp 3 ký tự liền kề nhau k được trùng
               if(strcmp($check1 , $check2) == 0 && strcmp($check2 , $check3) == 0)
                       return false;
                   // $temp =  false;
               //diều kiện 3 character đầu là string nếu vừa là string với number thì bỏ qua
               if(is_string($check1) && is_string($check2) && is_string($check3)){
                   $stringtest = $check1.$check2.$check3;
                   if(str_contains($regexString,$stringtest))
                       return false;
                           // $checkSTRING = false;
               }
               //diều kiện 3 character đầu number
               elseif(is_int($check1) && is_int($check2) && is_int($check3) ){
                   //sau đó convert lại để check
                   $stringNum = (string)$check1.(string)$check2.(string)$check3;
                   if(str_contains($regexNumber,$stringNum))
                       return false;
                       // $checkNum = false;
               }
           }
       }

      return true;
   }
  }
}



function decrypt_array($string)
{
    $crypt = urldecode($string);
    $crypt = base64_decode($crypt);
    $crypt = Crypt::decryptString($crypt);
    $crypt = json_decode($crypt, true);
    return $crypt;
}

function encrypt_array($data = [])
{
    $crypt = Crypt::encryptString(json_encode($data));
    $crypt = base64_encode($crypt);
    return urlencode($crypt);
}
