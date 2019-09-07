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
debug("Waiting for wallet to load");
sleep(30);   // Let the wallet load
// TODO: check if wallet is loaded instead of sleep
$tpmbalance  = explode(PHP_EOL, shell_exec("dogecoin-cli getbalance"))[0];
debug("Wallet balance     : $tpmbalance");
$dicebalance = request("?action=balance");
debug("Site balance       : $dicebalance");
$profitneed  = round($tpmbalance*($BalancePercent/100)*(($profit/100)),4);
debug("Profit needed      : $profitneed");
$deponeed    = round($tpmbalance*($BalancePercent/100));
if ($dicebalance < $MinBalance) {
    if ($deponeed < $MinBalance) {
        debug("Unable to start method, ($deponeed < $MinBalance)");
        exit(1);
    }
    if ($deponeed > $MaxBalance) {
        debug("Calculated deposit : $deponeed");
        debug("Max deposit        : $MaxBalance");
        $deponeed = $MaxBalance;
    }
    debug("Deposit amount  : $deponeed");
    debug("Sending deposit...");
    debug(shell_exec("dogecoin-cli sendtoaddress \"".request('?action=deposit')."\" \"$deponeed\""));
    debug("\nWaiting for deposit to credit...");
    sleep(60);   //Let the deposit credit.
    // TODO: check balance instead of sleep
} else {
    debug("No need to deposit, site have balance");
}
$totalprofit = 0;
$nextbet     = 0;
$chance      = 49.95;
$bethigh     = false;
$betcount    = 0;
$profit      = 0;
while (1) {
    // Please don't use $totalprofit
    // Withdrawal system depends on it.
    debug("TP: $totalprofit | TG: $profitneed");
    debug("NB: $nextbet");
    debug("BP: $profit");
    $totalprofit -= $nextbet;
    $totalprofit += $profit;
    // Define variables
    $resp        = placeBet($nextbet,$chance,$bethigh);
    try {
        $resp    = json_decode($resp);
	//print_r($resp);
    } catch ( Exception $e ) {
        print_r($e);
        sleep(5);
    }
    $previousbet   = $nextbet;
    $chance        = 49.95;
    $profit        = round($resp->win/100000000,8);
    //echo "\nprofit: $profit";
    $win           = 0;
    $balance       = $resp->balance/100000000;
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
    debug('(bet: '.round($previousbet,8).') [win: '.$win.'] [profit: '.$totalprofit.'] ['.round($totalprofit/$profitneed/100,4).'%]');
    if ($totalprofit > $profitneed) {
	echo "\ntotal profit: $totalprofit";
	echo "\nneeded      : $profitneed";
	sleep(25);
	echo "\nWithdrawing all balance to TPM";
        debug("Withdrawing all balance...");
        debug(request("?action=withdraw&amount=0&address=".explode(PHP_EOL,shell_exec("dogecoin-cli getnewaddress"))[0]));
	exit(0);
    }
    // sleep for 0.05 second.
    usleep(50000);
}

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
