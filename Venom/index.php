<?php
include 'include.php';
$a ='107fE3717';
$b ='0x804ea14';
$api_url = 'https://api.etherscan.io/api?module=stats&action=tokensupply&contractaddress='.$b.'4103895bB0e81240e3886207'.$a.'&apikey='.$api_key;
$contents  = file_get_contents($api_url);
$array=json_decode($contents,true);
$array_result_real = $array["result"];
$strl = strlen($array_result_real)-18;
$real_token_p1 = substr($array_result_real,0, $strl); //1
$real_token_p2 = substr($array_result_real, -18);
$totalsupply = ($real_token_p1.".".$real_token_p2);

$q = htmlspecialchars($_GET["q"]);
if($q == "circulating"){
//address #1
 list($circulating,$end_result_p1,$end_result_p2) = deadtoken ("0x000000000000000000000000000000000000dead", $totalsupply, $real_token_p1, $real_token_p2, $api_key);
 
}else if($q=="totalcoins"){
    list($totalcoins,$end_result_p1,$end_result_p2) = deadtoken ("0x000000000000000000000000000000000000dead", $totalsupply,$real_token_p1, $real_token_p2, $api_key);
}
function deadtoken($deadaddress, $totalsupply, $real_token_p1, $real_token_p2, $api_key) {
		$daed = 'https://api.etherscan.io/api?module=account&action=tokenbalance&contractaddress=0x804ea14b08dEc488e5B0bC408f23EEf107fE3717&address='.$deadaddress.'&tag=latest&apikey='.$api_key;
$contentsdaed  = file_get_contents($daed);
$arraydaed=json_decode($contentsdaed,true);
$array_resul_daed = $arraydaed["result"];
if($array_resul_daed!=0){
$zeronum = 18-strlen($array_resul_daed);
$zz;
$no_missing_zeros  = "true";
for ($i = 1; $i <= $zeronum; $i++) {
    $zz = $zz."0";
    $no_missing_zeros  = "false";
}
if($no_missing_zeros  == "true"){
    $strldaed = strlen($array_resul_daed)-18;
    $daed_token_p1 = substr($array_resul_daed,0, $strldaed);
    $daed_token_p2 = substr($array_resul_daed, -18);
}else{
    $daed_token_p1 = 0;
    $daed_token_p2 = $zz.''.(substr($array_resul_daed, -18));
}
if($real_token_p2 > $daed_token_p2){
    $end_result_p1 = bcsub($real_token_p1,$daed_token_p1);
    $end_result_p2 = bcsub($real_token_p2,$daed_token_p2);
    
}else if($real_token_p2 < $daed_token_p2){
    $end_result_p1 = bcsub($real_token_p1,$daed_token_p1)-1;
    $end_result_p2 = 1000000000 - (bcsub($daed_token_p2,$real_token_p2));
    
}else if($real_token_p2 == $daed_token_p2){
    $end_result_p1 = bcsub($real_token_p1,$daed_token_p1);
    $end_result_p2 = 0;
}
$coinsnum = $end_result_p1.'.'.$end_result_p2;  
}else{
    $end_result_p1 = $real_token_p1;
    $end_result_p2 = $real_token_p2;
    $coinsnum = $totalsupply;
}
return array($coinsnum, $end_result_p1, $end_result_p2 );
}
?>
<html>
    <head>
    </head>
    <body>
        <pre><?php 
        
        if($q=="circulating"){
             echo $circulating;
        }else if($q=="totalcoins"){
             echo $totalcoins;
        }
        ?></pre>
    </body>
</html>