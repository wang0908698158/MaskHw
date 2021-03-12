<?php

/** 
 * @i 用來跳過讀取到的csv檔案第一行
 * 
 * @correctInput 紀錄輸入和搜尋地址有匹配到
 * 
 * @row1,@row2,@row4 為陣列
 *      紀錄搜尋到的各項資料
 * 
 * @dataTest 初始化空陣列
 *      之後用於儲存要用climate輸出的資料
 * 
*/
$file = fopen("maskdata.csv", "r");
$i = 0;
$correctInput = 0;
$row1 = [-1];
$row2 = [-1];
$row4 = [-1];
$dataTest = array();

require_once('vendor/autoload.php');
$climate = new League\CLImate\CLImate;

while (($row = fgetcsv($file)) !== false){
    if($i > 0){
        /**
         * 比對資料是否包含輸入的字串
         */
        if(strpos($row[2], $argv[1]) !== false){
            $correctInput = 1;
            /**
             * 排列口罩數量由多到少
             * 並將相對應的其他資料移到正確位置
             */
            for($j = 0; $j < count($row4); $j++){
                if($row[4] > $row4[$j]){
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
/**
 * 將資料以climate規定的格式存入陣列
 */
for($j = 0; $j < count($row1) - 1; $j++){
    $dataTTest = array(
        "醫事機構名稱" => $row1[$j],
        "醫事機構地址" => $row2[$j],
        "成人口罩剩餘數" => $row4[$j]
    );
    $dataTest[] = $dataTTest;
}
/**
 * 輸入有匹配輸出匹配結果
 * 若無匹配 輸出else的結果
 */
if($correctInput == 1){
    $climate -> table($dataTest);
}
else{
    $climate->red('請確認正確地址');
    $climate->draw('404');
}
fclose($file);
?>
