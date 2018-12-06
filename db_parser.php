<?php
//Criando o banco =============================================================
if (file_exists('./database/db.php')){
  include "./database/db.php";
  echo "[+] DB loaded\n";
}
else{
    $cases = array();
    $GLOBALS['cases'] = $cases;
    $file = fopen('./database/lista_chaves.csv', 'r');
    $indices = fgetcsv($file);
    fclose($file);

    $file = fopen('./database/bcds.csv', 'r');
    while (($line = fgetcsv($file)) !== FALSE){
      foreach ($indices as $key => $indice) {
        $line[$indice] = $line[$key];
        unset($line[$key]);
      }
      $cases[$line['Caso']] = $line;
      unset($cases[$line['Caso']]['Caso']);
    }
    fclose($file);

    file_put_contents('./database/db.php', "<?php \$cases =" . var_export($cases, TRUE) . "?>");
    echo "[+] DB created\n";
}
//print_r($cases);
// ============================================================================

/* A Partir daqui, todos os valores de cada uma das tabelas, serão armazenados
de forma identificavel ao programa, em arrays. */

//Leitura dos pesos ===========================================================
$file = fopen('./database/pesos.csv', 'r');
$weights = array();
while (($line = fgetcsv($file)) !== FALSE){
  $weights[$line[0]] = $line[1];
}
fclose($file);
// ============================================================================

// print_r($weights['hail']);

//Leitura de 'valores de atributos' ===========================================
$file = fopen('./database/valores_de_atributos.csv', 'r');
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

// print_r($attributes['date']);
// print_r($cases[5]['date']);

function get_case_values($normal_case, $attributes){
    $normal_values = array();

    foreach ($normal_case as $key => $value) {
      if ($key == 'DescDoenca')
        continue;
      $normal_values[] = $attributes[$key][$value];
    }

    return $normal_values;
}

function get_extreme_values($cases, $attributes){
    $case_values = array();
    $size = sizeof($cases);
    $max = $min = 0;

    for ($i=1; $i < $size + 1; $i++) {
        $case_values[] = get_case_values($cases[$i], $attributes);
    }

    for ($i=1; $i < $size + 1; $i++) {
        if($case_values[$i][0] == "Sim" || $case_values[$i][0] == "Não"){
            break;
        }
        if($case_values[$i][0] > $max){
            $max = $case_values[$i][0];
        }
        if($case_values[$i][0] < $min){
            $min = $case_values[$i][0];
        }
    }

    echo 'Max:' . $max . 'Min:' . $min;
    // print_r($case_values[200][0]);
}

get_max_values($cases, $attributes);


// get_case_values($cases[1], $attributes);

// function similarity($cases, $normal_case, $problem_case, $attr){
//   // foreach($cases as $key => $value)
//   // {
//   //   print_r($key . " || " . $value);
//   // }
//
//   $normal_values = array();
//   foreach ($normal_case as $key => $value) {
//     if ($key == 'DescDoenca')
//       continue;
//     $normal_values[] = $attr[$key][$value];
//   }
//
//   $problem_values = array();
//   foreach ($problem_case as $key => $value) {
//     if ($key == 'DescDoenca')
//       continue;
//     $problem_values[] = $attr[$key][$value];
//   }
//
//   print_r($normal_values);
//
//   $total = 0;
//   for ($i = 0; $i < 35; $i++){
//     $total += ( 1 - abs( $normal_values[$i] - $problem_values[$i] ) / 1 );
//   }
//   return $total;
// }


// echo similarity($cases, $cases[1], $cases[17], $attributes);
echo "\n";
?>
