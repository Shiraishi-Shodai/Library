<?php

// このファイルは非同期通信で貸出処理や返却処理を行うためのファイル

// DAO.php,USER.php,Book.phpに保存されているPHPスクリプトを取り込む
require_once("db.php");

// Lent.phpまたはReturnLent.phpから送信されたuser_id,book_id,processingを受け取る
$user_id = filter_input(INPUT_POST, 'user_id');
$book_id = filter_input(INPUT_POST, 'book_id');
$process = filter_input(INPUT_POST, 'processing');

// DAOクラスをインスタンス化
$dao = new DAO();

// process変数がlentと等しいとき、貸出処理を行う,process変数がreturnLentと等しいとき、返却処理を行う
if (strcmp($process, "lent") == 0) {
    // 貸出処理を実行
    echo $dao->lendingProcess($user_id, $book_id);
} else if (strcmp($process, "returnLent") == 0) {
    // 返却処理を実行
    echo $dao->returnProcess($user_id, $book_id);
}

// このファイルの処理を終了する
exit;
