cd "/home/`whoami`/TPM/mods/betmeup";
ports=`cat ports.txt`;
cd "/home/`whoami`/TPM/mods/betmeup/method"

for f in *;
do
    for g in "$ports.$f.conf";
    do
        php startscript.php $f 127.0.0.1 $g
    done
done;
