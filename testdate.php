<?php

/*
$date = new DateTime('2012-12-31');

echo $date->format('Y\WW');
*/

$date = new DateTime();
$date->setISODate(2013, 1, 8);
echo $date->format('Y-m-d');