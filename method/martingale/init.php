<?php
// Get balance;
//debug("Waiting for wallet to load");
//sleep(30);   // Let the wallet load
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
