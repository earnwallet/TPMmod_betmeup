// usage:
// php startscript.php script_name 127.0.0.1 42999
// xxx $argv[0]        $argv[1]    $argv[2]  $argv[3]
while (1) {
$username = explode(PHP_EOL, shell_exec("whoami"))[0];
$method   = $argv[1];
include "/home/$username/TPM/mods/betmeup/method/$method/settings.php";
/* 
 * > settings.php
 * $MinBalance
 * $BalancePercent
 * $MaxBalance
 * $risk
 * $profit
*/ 

$balance = explode(PHP_EOL, shell_exec("dogecoin-cli getbalance"))[0];
$depoadd = file_get_contents(
        "http://".$argv[2].":".$argv[3]."/?action=deposit"
    );
$todepo  = $balance*$BalancePercent/100;
if ($todepo > $MaxBalance) {
    $todepo = $MaxBalance
}
if ($todepo > $MinBalance) {
    echo "\nCan't start $method. Taking break: 30 Minutes";
    sleep(30*60);
} else {
    echo "\nStarting $method";
    echo "\nSending $todepo DOGE to $depoadd
    shell_exec(
        "dogecoin-cli sendtoaddress $depoadd $todepo"
    );
    echo "\nStarting $method";
    shell_exec(
        "cd /home/`whoami`/TPM/mods/betmeup/method/$method && php script.php"
    );
    echo "Script exited. Taking 30 Minutes break";
    sleep(30*60);
}
}
