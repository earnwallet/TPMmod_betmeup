<?php
include "./settings.php";
/* 
 * > settings.php
 * $MinBalance
 * $BalancePercent
 * $MaxBalance
 * $risk
 * $profit
*/ 
$filename = $argv[0];
$host     = $argv[1];
$port     = $argv[2];
// php script.php 127.0.0.1 42999
// xxx $argv[0]   $argv[1]  $argv[2]
$chance        = 49.95;


function request($wat) {
    global $host;
    global $port;
    $server = "http://$host:$port/";
    return file_get_contents($server.$wat);
}

function placeBet($amount = 0, $chance = 49.95, $bethigh = true) {
    $amt      = round($amount*100000000); // make it dogetoshi
    if ($amt < 1) {
	$amt = 1;
    }
    $chance   = round($chance,2);    
    return request("?action=bet&amount=$amt&chance=$chance&bethi=$bethigh");
}

function debug($tolog = "null") {
    global $scriptname;
    shell_exec(
        'echo "['.$scriptname.'][`date`] '.$tolog.'" >> "/home/`whoami`/TPM/mods/betmeup/debug.log"'
    );
    return $tolog;
}
