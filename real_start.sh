cd "/home/`whoami`/TPM/mods/betmeup/method"

for f in *;
do
    for g in $("cat /home/`whoami`/TPM/mods/betmeup/method/$f/ports.conf");
    do
        cd "/home/`whoami`/TPM/mods/betmeup/method/$f";
        screen -dm php startscript.php $f 127.0.0.1 $g;
        cd "/home/`whoami`/TPM/mods/betmeup/method"
    done
done;
