<?php
if (file_exists('./db.php'))
{
  include "./db.php";
  echo "[ + ] DB loaded\n";
}
else
{
    $cases = array();
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
      $cases[] = $line;
    }
    fclose($file);

    file_put_contents('./db.php', "<?php \$cases =" . var_export($cases, TRUE) . "?>");
    echo "[ + ] DB created\n";
}
//Lendo pesos
$file = fopen('pesos.csv', 'r');
$weights = array();
while (($line = fgetcsv($file)) !== FALSE)
{
  $weights[$line[0]] = $line[1];
}

fclose($file);
//print_r($weights);

//Lendo valores de atributos
$file = fopen('valores_de_atributos.csv', 'r');
$attributes = array();
while (($line = fgetcsv($file)) !== FALSE)
{
  if ($line[0] != "")
  {
    $key = $line[0];
    $attributes[$key][$line[2]] = $line[3];
  }
  else
    $attributes[$key][$line[2]] = $line[1];
}
fclose($file);
print_r($attributes);





 ?>
