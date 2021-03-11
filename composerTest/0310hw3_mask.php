<?php

$file = fopen("maskdata.csv", "r");
//$input = $argv[1];
$i = 0;
$string = "";
$correctInput = 0;

$row1 = [-1];
$row2 = [-1];
$row4 = [-1];

//$dataSearch = array();
$dataTest = array();

require_once('vendor/autoload.php');
$climate = new League\CLImate\CLImate;

while (($row = fgetcsv($file)) !== false){
    if($i > 0){
        if(strpos($row[2], $argv[1]) !== false){   
            $correctInput = 1;
            for($j = 0; $j < count($row4); $j++){
                if($row[4] > $row4[$j]){
                    /*$dataFind = array(
                        "成人口罩剩餘數" => $row[4],
                        "醫事機構地址" => $row[2],
                        "醫事機構名稱" => $row[1]
                    );
                    $dataSearch[] = $dataFind;*/
                    array_splice($row4, $j, 0, $row[4]);
                    array_splice($row2, $j, 0, $row[2]);
                    array_splice($row1, $j, 0, $row[1]);
                    break;
                }
            }
        }
    }
    $i++;
}
//sort($dataSearch);
//$climate->table($dataSearch);
for($j = 0; $j < count($row1) - 1; $j++){
    $dataTTest = array(
        "醫事機構名稱" => $row1[$j],
        "醫事機構地址" => $row2[$j],
        "成人口罩剩餘數" => $row4[$j]
    );
    $dataTest[] = $dataTTest;
}
if($correctInput == 1){
    $climate -> table($dataTest);
}
else{
    $climate->red('請確認正確地址');
    $climate->draw('404');
}
fclose($file);
?>