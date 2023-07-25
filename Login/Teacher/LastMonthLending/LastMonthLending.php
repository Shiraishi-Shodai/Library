<?php
// DAO.php,USER.php,Book.phpに保存されているPHPスクリプトを取り込む
require_once("../../../Test_DB/db.php");
require_once("../../../Test_DB/User.php");

session_start();

// user_idがNULLまたは空の時、ログインページに遷移させる
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../Login.php");
    exit;
}
// DAOクラスをインスタンス化
$dao = new DAO();
// 引数にセッション変数のuser_idを指定してDAOクラスのgetUserメソッドを実行しUserインスタンスを変数userに格納
$user = $dao->getUser($_SESSION["user_id"]);
// DAOクラスのgetLastMonthLending関数を呼び出し、先月のクラスの貸出情報を取得
$lastMonthLending = $dao->getLastMonthLending($user->getAffiliationId());

$lent_time = [];
$count = [];
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
$command = "echo $json_data | python ./index.py";
// index.pyにjson_dataを渡す。pythonでprintしたものをoutputで受け取る
exec($command, $output);
