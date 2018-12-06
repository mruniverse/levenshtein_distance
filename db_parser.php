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

//echo $weights['date'];
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

// print_r($attributes['leaf-mild']);
// print_r($cases[12]['leaf-mild']);

// ============================================================================
function get_case_values($normal_case, $attributes){
    $normal_values = array();

    foreach ($normal_case as $key => $value) {
      if ($key == 'DescDoenca')
        continue;
      $normal_values[] = $attributes[$key][$value];
    }

    return $normal_values;
}
// print_r(get_case_values($cases[1], $attributes));

// ============================================================================
function get_extreme_values($cases, $attributes, $choice, $att_number){
    // $att_number = number of the attribute of the column, from 0 to 35.
    $size = sizeof($cases); //How many cases there are on the casebase.
    $max = $min = 0;

    $case_values = array();
    for ($i=1; $i <= $size; $i++) {
        $case_values[] = get_case_values($cases[$i], $attributes);
    }

    for ($i=1; $i <= $size; $i++) {
        if($case_values[$i][$att_number] == "Sim" || $case_values[$i][$att_number] == "Não"){
            break;
        }
        if($case_values[$i][$att_number] > $max){
            $max = $case_values[$i][$att_number];
        }
        if($case_values[$i][$att_number] < $min){
            $min = $case_values[$i][$att_number];
        }
    }
    // echo 'Max:' . $max . 'Min:' . $min;
    // print_r($case_values[200][0]);
    if($choice == "max"){
        return ($max == 0) ? 1 : $max;
    }
    else if($choice == "min"){
        return $min;//$min;
    }
    else{
        return "Please, coose between 'max' and 'min'.";
    }
}
// ============================================================================
// echo get_extreme_values($cases, $attributes, "max", 3);


// ============================================================================
function similarity($cases, $normal_case, $problem_case, $attr, $weights){
  // foreach($cases as $key => $value)
  // {
  //   print_r($key . " || " . $value);
  // }

  $normal_values = array();
  foreach ($normal_case as $key => $value) {
    if ($key == 'DescDoenca')
      continue;
    $normal_values[] = $attr[$key][$value];
  }

  $problem_values = array();
  foreach ($problem_case as $key => $value) {
    if ($key == 'DescDoenca')
      continue;
    $problem_values[] = $attr[$key][$value];
  }

  $total = 0;
  for ($i = 1; $i < 35; $i++){
      if(get_extreme_values($cases, $attr, "max", $i) == 0){
          // print_r($cases[1][$attr[$i]]);
          echo get_extreme_values($cases, $attr, "max", $i);
          echo $i . "\n";
      }
      $attr_name = array_keys($cases[2])[$i];

     $total += (1 - abs( $normal_values[$i] - $problem_values[$i] )
          / (get_extreme_values($cases, $attr, "max", $i) -
          get_extreme_values($cases, $attr, "min", $i)) ) * $weights[$attr_name];

  }
  return ($total / 210);
}
  for ($i = 1; $i<sizeof($cases); $i++)
  {
    printf("Caso(%d) com Caso(%d) = %.2f%%\n", $i, 17,
      similarity($cases, $cases[$i], $cases[17], $attributes, $weights) * 100);
  }
?>
