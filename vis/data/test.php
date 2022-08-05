<?php

$arr = [
  ['label' => 'Mike', 'group' => 1],
  ['label' => 'Bob', 'group' => 1],
  ['label' => 'Tom', 'group' => 1],
];

print_r($arr);


$sub = 'Tom';
$key = array_search($sub, array_column($arr, 'label'));

if(empty($key)) echo "No one found\n";
else echo "Person has the id of$key\n";