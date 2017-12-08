<?php
/**
 * Created by PhpStorm.
 * User: Fabien
 * Date: 01/06/2016
 * Time: 14:01
 */

public function read()
    {
        $output = array();
        $return_var = 0;
        $i=1;
//        exec('/usr/local/bin/loldht 7', $output, $return_var);
            if ($return_var > $i){
            while (substr($output[$i], 0, 1) != "H") {
               $i++;
            }
            echo $output[$i];
            }else {
                echo 'error <br>';

            }
    }


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
read(0);

?>