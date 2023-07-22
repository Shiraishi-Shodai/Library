<?php

// DAO.php,USER.php,Book.phpに保存されているPHPスクリプトを取り込む
require_once("db.php");

$affiliation_id = filter_input(INPUT_POST, 'affiliation_id');
$processing = filter_input(INPUT_POST, 'processing');

// DAOクラスをインスタンス化
$dao = new DAO();

// DAOクラスのgetLastMonthLending関数を呼び出し、先月のクラスの貸出情報を取得
$lastMonthLending = $dao->getLastMonthLending($affiliation_id);

$lent_time = [];
$count = [];

// データベースから取得したデータをデータの種類別にそれぞれのリストに追加
foreach ($lastMonthLending as $last) {
    $lent_time[] = $last["lent_time"];
    $count[] = $last["count"];
}

$data = array(

    "lent_time" => $lent_time,
    "count" => $count
);
$json_data = json_encode($data);

// index.pyにjson_dataを渡す準備
$command = "echo $json_data | python ../Login/Student/LastMonthLending/index.py";
// index.pyにjson_dataを渡す。pythonでprintしたものをoutputで受け取る
echo exec($command, $output);

exit;
