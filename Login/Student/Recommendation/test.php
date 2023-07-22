<?php

$a = 2;

$b = array("b" => 1, "c" => 2);
$b = json_encode($b);
$command = "echo $b | python ./test.py";
exec($command, $output);

var_dump($output[0]);
var_dump($output[1]);
