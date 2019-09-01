cd "/home/`whoami`/TPM/mods/betmeup/method"

for f in *;
do
    echo " - Staring $f";
    cd "/home/`whoami`/TPM/mods/betmeup/method/";
    ports=`cat $f/ports.conf`;
    for g in $ports;
    do
        echo "   - Port: $g";
        cd "/home/`whoami`/TPM/mods/betmeup/";
        screen -dm php startscript.php $f 127.0.0.1 $g;
        cd "/home/`whoami`/TPM/mods/betmeup/method";
    done
done;
