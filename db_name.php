<?php
$name_array = array();
if (file_exists('./db.php'))
{
  include "./db.php";
}

$file = fopen('lista_chaves.csv', 'r');
$indices = fgetcsv($file);
fclose($file);

$file = fopen('bcds.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE)
{

foreach ($indices as $key => $indice) {
  $line[$indice] = $line[$key];
  unset($line[$key]);
}
/*
  $line['Caso'] = $line[0];
  unset($line[0]);

  $line['DescDoenca'] = $line[1];
  unset($line[1]);

  $line['area-damaged'] = $line[2];
  unset($line[2]);

*/
$name_array[] = $line;
}
fclose($file);

echo "[ + ] DB created\n";
file_put_contents('./db.php', "<?php \$name_array =" . var_export($name_array, TRUE) . "?>");

//var_dump($name_array);
 ?>
