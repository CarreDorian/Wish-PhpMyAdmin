<?php
include 'data_manipulation.php';

$count = 1;

$GLOBALS['name'] = 'cli.php';
function help(){
    echo "================ help menu ================\n";
    echo "php ", $GLOBALS['name'], " <function_to_use> <parameters>\n";
    echo "You can execute multiple function with the same model\n\n";
    echo "read <file_to_read> : read a local csv file\n";
    echo "line_by_range -start <int1> -end <int2> : take from the <int1> to the <int2> index\n";
    echo "save <name_file> : save the result in a local csv file <name_file>\n";
    echo "select_columns <columns1> <columns2> ... -end : conserve onely the column 1, 2, ...\n";
    echo "delate_columns <columns1> <columns2> ... -end : delate the column 1, 2, ...\n\n";
    echo "Options : \n";
    echo "-h, -help : show this message\n";
}

// function flags(){
//     $count = $argv[$GLOBALS["count"]];
//     do {
//         $GLOBALS["count"] += 1;
//         $test = parcing($argv, $GLOBALS["count"]);
//         if ($test == "ERROR : missing argument")
//             return 0;
//     } while ($test != 0);
// }

function parcing($argv){
    if (!isset($argv[$GLOBALS["count"]]))
        return "ERROR : missing argument";

    switch ($argv[$GLOBALS["count"]][0]){
        // setup the option :
        case "-":
            // the word option :
            $word = substr($argv[$GLOBALS["count"]], 1);
            switch ($word){
                case "h":
                case "-help":
                    help();
                    $GLOBALS["count"] += 1;
                    break;
            }
            break;
        default:
            switch ($argv[$GLOBALS["count"]]){
                case "read":
                    $GLOBALS["count"]+=1;
                    $GLOBALS["results"] = read($argv[$GLOBALS["count"]]);
                    break;

                case "line_by_range":
                    $GLOBALS["count"]+=1;
                    if (isset($GLOBALS["results"])) {
                        $start_line = 0;
                        $end_line = -1;
                        $bool = TRUE;
                        do {
                            if (!isset($argv[$GLOBALS["count"]])) break;
                            $to_test = $argv[$GLOBALS["count"]];
                            switch ($to_test) {
                                case "-start":
                                    $GLOBALS["count"]+=1;
                                    $start_line = ((int) $argv[$GLOBALS["count"]]);
                                    break;
                                case "-end":
                                    $GLOBALS["count"]+=1;
                                    $end_line = ((int) $argv[$GLOBALS["count"]]);
                                    break;
                                default:
                                    $bool = False;
                            }
                            $GLOBALS["count"]+=1;
                        } while ($bool);
                        $GLOBALS["count"]-=1;
                        $GLOBALS["results"] = line_by_range($GLOBALS["results"], $start_line, $end_line);
                    }
                    break;

                case "save":
                    $GLOBALS["count"] += 1;
                    if (isset($GLOBALS["results"]) AND isset($argv[$GLOBALS["count"]])) {
                        save($GLOBALS["results"], $argv[$GLOBALS["count"]]);
                        echo "has been save : \n";
                        tableau($GLOBALS["results"]);
                        echo "\n";
                    } else echo "No Results to save\n";
                    $GLOBALS["count"] += 1;
                    break;

                case "select_columns":
                    $GLOBALS["count"] += 1;
                    $columns = [];
                    while (isset($argv[$GLOBALS["count"]])) {
                        if ($argv[$GLOBALS["count"]] == "-end")
                            break;
                        array_push($columns, $argv[$GLOBALS["count"]]);
                        $GLOBALS["count"] += 1;
                    }
                    $GLOBALS["count"] += 1;
                    if (isset($GLOBALS["results"]))
                        $GLOBALS["results"] = select_column($GLOBALS["results"], $columns);
                    break;
                    
                case "delate_columns":
                    $GLOBALS["count"] += 1;
                    $columns = [];
                    while (isset($argv[$GLOBALS["count"]])) {
                        if ($argv[$GLOBALS["count"]] == "-end")
                            break;
                        array_push($columns, $argv[$GLOBALS["count"]]);
                        $GLOBALS["count"] += 1;
                    }
                    $GLOBALS["count"] += 1;
                    print_r($columns);

                    if (isset($GLOBALS["results"]))
                        $GLOBALS["results"] = delate_column($GLOBALS["results"], $columns);
                    break;
    
                default:
                    $GLOBALS["count"] += 1;
                    return 0;
            }
    }
    return 1;
}

while ($GLOBALS["count"] < $argc)
    parcing($argv);

if (isset($GLOBALS["results"])) {
    echo "The result of the command line :\n";
    tableau($GLOBALS["results"]);
}

// include 'src/advenced_en_decode.php';

// // check that all parameters exist
// if (!isset($argv[1])) echo "parrameters forget\n";
// elseif (!isset($argv[2])) echo "parrameters forget\n";
// elseif (!isset($argv[3])) echo "parrameters forget\n";
// else {

//     // verification of the action to do.
//     switch ($argv[1]) {
//         // compress the file give in first argument
//         case "encode":
//             if (encode_advanced_rle($argv[2], $argv[3])) echo "ERROR : encode\n";
//             else echo "OK\n";
//             break;
//         // decompress the file give in first argument
//         case "decode":
//             if (decode_advanced_rle($argv[2], $argv[3])) echo "ERROR : decode\n";
//             else echo "OK\n";
//             break;
//         // compress file give in first argument and decompress it
//         case "duo":
//             if (encode_advanced_rle($argv[2], $argv[3])) {
//                 if (!isset($argv[4])) echo "parrameters forget\n";
//                 echo "ERROR : encode\n";
//                 break;
//             } else echo "OK - ";
//             if (decode_advanced_rle($argv[3], $argv[4])) echo "ERROR : decode\n";
//             else echo "OK\n";
//             break;
//         default:
//             echo "ERROR : bad parrameters\n";
//     }

// }
