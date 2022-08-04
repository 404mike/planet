<?php

$json = file_get_contents('location_issues.json');

$data = json_decode($json,true);

foreach($data as $K => $v) {
  echo "$v,\n";
}