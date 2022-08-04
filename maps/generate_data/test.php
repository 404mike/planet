<?php

$str = 'Aug-2019';

$arr = explode('-',$str);

$year = $arr[1];
$month = $arr[0];

$newDate = $year . '-' . $month . '-01';
$n = date('Ymd',strtotime($newDate));

echo "$n\n";