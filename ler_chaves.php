<?php
$file = fopen('lista_chaves.csv', 'r');
$indices = fgetcsv($file);
  print_r($indices);
fclose($file)


 ?>
