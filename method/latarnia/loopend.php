<?php
    debug('(bet #'.$betcount.': '.number_format(round($previousbet,8), 8, '.', '').') [win: '.round($win).'] ['.round($totalprofit,2).' of '.round($profitneed,2).']');
    if ($totalprofit > $profitneed && $profitneed != 0) {
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

