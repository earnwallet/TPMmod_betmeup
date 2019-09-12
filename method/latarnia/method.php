<?php
$chance       = 90;
$base         = $balance/200000;
if ($base < 0.00000001) {
    $base = 0.00000001;
}
if ($highestbal < $balance) {
    debug("We are on top! (HB: $highestbal| BAL: $balance)");
    $nextbet    = $base;
    $highestbal = $balance;
} else {
    debug("Rebuilding... (HB: $highestbal| BAL: $balance)");
    $chance = 77.77;
    if ($win) {
        $nextbet = $previousbet*(rand(70,130)/100);
    } else {
        $nextbet = $previousbet*(rand(150,350)/100);
    }
}
while ($nextbet*10 > $balance && rand(0,50) < 48 && $nextbet > $base) {
    $nextbet = $nextbet/rand(1,15);
}
if ($nextbet < 0.000001) {
    $nextbet = 0.01;
}
