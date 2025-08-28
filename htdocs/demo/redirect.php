<?php
$agent = $_SERVER['HTTP_USER_AGENT']; 
if(ereg("^DoCoMo", $agent)){
	$career = "docomo";
}else if(ereg("^J-PHONE|^Vodafone|^SoftBank", $agent)){
	$career = "softbank";
}else if(ereg("^UP.Browser|^KDDI", $agent)){
	$career = "au";
}else{
	$career = "pc";
}

if($career == "pc")
	header("Location:http://page.mixi.jp/view_page.pl?page_id=203016");
else
	header("Location:http://page.m.mixi.jp/view_page.pl?page_id=203016");

exit;
?>