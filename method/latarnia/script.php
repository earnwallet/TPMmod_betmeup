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

// Get balance;
sleep(30);   // Let the wallet load
// TODO: check if wallet is loaded instead of sleep
$tpmbalance  = explode(PHP_EOL, shell_exec(`dogecoin-cli getbalance`))[0];
$dicebalance = request("?action=balance");
$profitneed  = round($tpmbalance*($BalancePercent/100)*(($profit/100)+1),4);
$totalprofit = -2; // Fee for withdrawal+deposit
$nextbet     = 0;
$chance      = 49.95;
$bethigh     = false;
sleep(60);   //Let the deposit credit.
// TODO: check balance instead of sleep
while (1) {
    // Please don't use $totalprofit
    // Withdrawal system depends on it.
    $totalprofit -= $nextbet;
    $totalprofit += $profit;
    // Define variables
    $resp        = placeBet($nextbet,$chance,$bethigh);
    try {
        $resp    = json_decode($resp);
    } catch ( Exception $e ) {
        print_r($e);
        sleep(5);
    }
    $previousbet   = $nextbet;
    $chance        = 49.95;
    $profit        = $resp->win/10^8;
    $win           = 0;
    $balance       = $resp->balance;
    if ($resp->win == 0) {
        $win       = 0;
    } else {
        $win       = 1;
    }
    $betcount++;
    $currentstreak = 0;
    if ($win) {
        if ($currentstreak < 0) {
            $currentstreak = 0;
        }
        $currentstreak++;
    } else {
        if ($currentstreak > 0) {
            $currentstreak = 0;
        }
        $currentstreak--;
    }
    // Do betting here





    $chance       = 49.95;
    $base         = $balance/1000000;
    if ($base < 0.00000001) {
        $base = 0.00000001;
    }
    if ($win) {
        $nextbet = $base;
    } else {
        $nextbet = $previousbet*2;
    }





    // Do not betting here
    shell_exec(
        'echo "[`date`] (bet: '.round($previousbet*10^8,8).') [win: '.$win.'] [profit: '.$totalprofit.'] ['.round($profitneed/$totalprofit/100,2).'%] >> "/home/`whoami`/TPM/mods/betmeup/debug.log"'
    );
    if ($totalprofit > $profitneed) {
        request("?action=withdraw&amount=0&address=".explode(PHP_EOL,shell_exec(`dogecoin-cli getnewaddress`)[0]);
    }
    // sleep for 0.1 second.
    usleep(100000);
}

function request($wat) {
    global $host;
    global $port;
    $server = "http://$host:$port/";
    return file_get_contents($server.$wat);
}

function placeBet($amount = 0, $chance = 49.95, $bethigh = true) {
    $amt      = round($amount*10^8); // make it dogetoshi
    $chance   = round($chance,2);    
    return request("?action=bet&amount=$amt&chance=$chance&bethi=$bethigh");
}