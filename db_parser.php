<?php
//Criando o banco =============================================================
if (file_exists('./database/db.php')){
  include "./database/db.php";
  echo "[+] DB loaded\n";
}
else{
    $cases = array();
    $file = fopen('/database/lista_chaves.csv', 'r');
    $indices = fgetcsv($file);
    fclose($file);

    $file = fopen('/database/bcds.csv', 'r');
    while (($line = fgetcsv($file)) !== FALSE){
      foreach ($indices as $key => $indice) {
        $line[$indice] = $line[$key];
        unset($line[$key]);
      }
      $cases[] = $line;
    }
    fclose($file);

    file_put_contents('./database/db.php', "<?php \$cases =" . var_export($cases, TRUE) . "?>");
    echo "[+] DB created\n";
}
// ============================================================================

/* A Partir daqui, todos os valores de cada uma das tabelas, serão armazenados
de forma identificavel ao programa, em arrays. */

//Leitura dos pesos ===========================================================
$file = fopen('/database/pesos.csv', 'r');
$weights = array();
while (($line = fgetcsv($file)) !== FALSE){
  $weights[$line[0]] = $line[1];
}
fclose($file);
// ============================================================================

//print_r($weights);

//Leitura de 'valores de atributos' ===========================================
$file = fopen('/database/valores_de_atributos.csv', 'r');
$attributes = array();
while (($line = fgetcsv($file)) !== FALSE){
  if ($line[0] != ""){
    $key = $line[0];
    $attributes[$key][$line[2]] = $line[3];
  }
  else
    $attributes[$key][$line[2]] = $line[1];
}
fclose($file);
// ============================================================================

print_r($attributes);


?>
