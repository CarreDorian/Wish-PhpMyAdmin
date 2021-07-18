<?PHP
  function read($csv){
    $file = fopen($csv, 'r');
    if (!feof($file) ){
      foreach (fgetcsv($file) as $key){
        $keys[] = trim($key);
        $elements[trim($key)] = trim($key);
      }
      $result["column"] = $elements;
    }
    while (!feof($file) ) {
      $count = 0;
      $line = fgetcsv($file);
      if ($line != array()){
        foreach ($line as $data) {
          $elements[$keys[$count]] = trim($data);
          $count += 1;
        }
        $result[] = $elements;
      }
    }
    $result["index"] = array_keys($result);
    $result["index"][0] = "";
    return $result;
  }

  function save($csv, $file){
    $file = fopen($file, 'w');
    $column = $csv["column"];
    unset($csv["index"]);
    foreach ($csv as $to_save)
      fputcsv($file, $to_save);
    fclose($file);
  }

  function index_same_len($index){
    $max_len = 0;
    foreach($index as &$line){
      $len = strlen($line);
      if ($max_len < $len)
        $max_len = $len;
    }
    foreach($index as &$line){
      while (strlen($line) < $max_len)
        $line = substr("$line                        ", 0, $max_len);
    }
    return $index;
  }

  function same_len($tbl, $replay=TRUE){
    if(!empty($tbl["index"])){
      $index = $tbl["index"];
      unset($tbl["index"]);
    }
    foreach($tbl["column"] as $column){
      $max_len = 0;
      foreach($tbl as &$line){
        $len = strlen($line[$column]);
        if ($max_len < $len)
          $max_len = $len;
      }
      foreach($tbl as &$line){
        while (strlen($line[$column]) < $max_len)
         $line[$column] = substr("$line[$column]                        ", 0, $max_len);
      }
    }
    $tbl["index"] = index_same_len($index);
    return $tbl;
  }

  function line_by_range($tbl, $start_line = 0, $end_line = -1){
    if ($end_line == -1)
      $end_line = count($tbl);

    $elements["column"] = $tbl["column"];
    $elements["index"][] = $tbl["index"][0];
    unset($tbl["index"][0]);
    $count = 0;
    foreach($tbl["index"] as $element){
      if ($start_line <= $element AND $element <= $end_line){
        $elements["index"][] = $element;
        $elements[$tbl["index"][$element + 1]] = $tbl[$element];
      }
    }
    $elements["index"][0] = "";
    return $elements;
  }

  function lines_by_selection($tbl, $lines){
    $elements["column"] = $tbl["column"];
    $elements["index"][] = $tbl["index"][0];
    unset($tbl["index"][0]);
    $count = 0;
    foreach($tbl["index"] as $element){
      if (in_array($element, $lines)){
        $elements["index"][] = $element;
        $elements[$tbl["index"][$element + 1]] = $tbl[$element];
      }
    }
    $elements["index"][0] = "";
    return $elements;
  }

  function select_column($tbl, $columns){
    if ($columns == [])
      return $tbl;
    
    $elements["index"] = $tbl["index"];
    unset($tbl["index"]);
    $index = array_keys($tbl);
    foreach($columns as $column){
      $count = 0;
      foreach($tbl as $element)
        $elements[$index[$count++]][$column] = $element[$column];
    }
    return $elements;
  }

  function delate_column($tbl, $columns){
    $index = $tbl["index"];
    $index[0] = "column";
    unset($tbl["index"]);

    foreach($index as $line)
      foreach($columns as $column)
        if (isset($tbl[$line][$column]))
          unset($tbl[$line][$column]);

    print_r($tbl);
    $index[0] = "";
    $tbl["index"] = $index;
    return $tbl;
  }

  function tableau($tbl){
    $tbl = same_len($tbl);
    $count = 0;
    $index = $tbl["index"];
    unset($tbl["index"]);
    foreach($tbl as $data){
      $id = $index[$count++];
      echo "| $id | ";
      foreach($data as $elements)
        echo "$elements | ";
      echo"\n";
    }
  }


  // Définir le chemin d'accès au fichier CSV
// $csv = 'test.csv';
// $csv = read($csv);
// $csv = line_by_range($csv);
// tableau($csv);
// save($csv, "coucou.csv");