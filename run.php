<?php
// Ilyasa Fathur Rahman
// SGB-Team Reborn
set_time_limit(0);
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
echo '####################################';
echo "\r\n";
echo '# Copyright : @ilyasa48 | SGB-Team #';
echo "\r\n";
echo '####################################';
echo "\r\n";
echo 'Masukkan Kode Referral : '; 
$code = trim(fgets(STDIN)); 
echo 'Masukkan Jumlah : '; 
$jumlah = trim(fgets(STDIN)); 
$i=1;
while($i <= $jumlah){
echo "\r\n";
echo "[".date('h:i:s')."] [$i/$jumlah] Membuat email...";
echo "\r\n";
ulang_register:

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://froidcode.com/api/bigtoken/register.php?referral=$code");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
$register = json_decode($result);

if($register->content == "Email telah digunakan"){
    echo "[".date('h:i:s')."] [$i/$jumlah] Email telah digunakan, membuat email ulang...";
    echo "\r\n";
    goto ulang_register;
}else if($register->content == "Gagal, Terjadi Kesalahan [1]"){
    echo "[".date('h:i:s')."] [$i/$jumlah] Gagal, Terjadi Kesalahan [1]";
    echo "\r\n";
    goto ulang_register;
}

$email = $register->content;

echo "[".date('h:i:s')."] [$i/$jumlah] Berhasil membuat email, memeriksa email...";
echo "\r\n";
ulang_email:
sleep(10);
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://froidcode.com/api/bigtoken/get_email.php?email=$email");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
$get_email = json_decode($result);


if($get_email->content == "Email belum masuk"){
    echo "[$i] Email belum masuk, memeriksa email ulang...";
    echo "\r\n";
    goto ulang_email;
}

$url = $get_email->content;

echo "[".date('h:i:s')."] [$i/$jumlah] Melakukan verifikasi...";
echo "\r\n";
ulang_redeem:
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://froidcode.com/api/bigtoken/finish.php?email=$email&url=$url");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
$finish = json_decode($result);

if($finish->result == "1"){
    echo "[".date('h:i:s')."] [$i/$jumlah] ".$finish->content;
    echo "\r\n";
}else if($finish->result == "0"){
    echo "[".date('h:i:s')."] [$i/$jumlah] ".$finish->content;
    echo "\r\n";
}
$i++;
}
