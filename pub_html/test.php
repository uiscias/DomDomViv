<?php
function readSensor($sensor)
{
    $output = array();
    $return_var = 0;
    $i=1;
    exec('sudo /usr/local/bin/loldht '.$sensor, $output, $return_var);
    while (substr($output[$i],0,1)!="H")
    {
        $i++;
    }
    $humid=substr($output[$i],11,5);
    $temp=substr($output[$i],33,5);
    return;
}
readSensor(0);
?>
