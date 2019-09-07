<?php
include './vars.php';
include './init.php';
while (1) {
    include './loopbegin.php';
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
    include './loopend.php';
}
