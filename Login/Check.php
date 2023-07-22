<?php

// DAO.php,USER.phpに保存されているPHPスクリプトを取り込む
require_once("../Test_DB/db.php");
require_once("../Test_DB/User.php");

// 入力されたログインIDとパスワードをPOST変数から受け取る
$login_id = $_POST["login_id"];
$password = $_POST["password"];

// データベースに接続
$dao = new DAO();
// ユーザーオブジェクトを生成
$user = $dao->getUser($login_id);
// 入力したログインIDに対応するパスワードをanswerに格納
$answer = $user->getPassword();

// ログイン成功時の処理
if ($answer !== null && $answer == $password) {

  session_start();
  // UserオブジェクトからUserIDを取得し、セッション変数にuser_idとして格納
  $_SESSION["user_id"] = $user->getUserId();
  // ログインしたユーザーのuser_type_idを変数user_type_idに格納
  $user_type_id = $user->getUserTypeId();

  // user_type_idが1なら学生用ページへ、2なら管理者用ページへ、どちらでもないならログインエラー表示ページへ遷移
  if ($user_type_id == 1) {
    header("Location: Student/Home/StudentHome.php"); //学生用のホーム画面に遷移
    exit;
  } elseif ($user_type_id == 2) {
    header("Location: Teacher/Edit/Edit.php"); //教員用のホーム画面に遷移
    exit;
  } else {
    echo "ユーザ種別が無効です。";
  }
} else {
  // ログイン失敗時の処理
  header("Location: LoginError.php");
}
