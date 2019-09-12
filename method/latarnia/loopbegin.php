<?php
// Please don't use $totalprofit
// Withdrawal system depends on it.
//debug("TP: $totalprofit | TG: $profitneed");
//debug("NB: $nextbet");
//debug("BP: $profit");
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
