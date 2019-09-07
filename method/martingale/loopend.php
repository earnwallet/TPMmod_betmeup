<?php
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

