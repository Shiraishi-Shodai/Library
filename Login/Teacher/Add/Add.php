<?php
// DAO.php,USER.php,Book.phpに保存されているPHPスクリプトを取り込む
require_once("../../../Test_DB/db.php");
require_once("../../../Test_DB/User.php");
require_once("../../../Test_DB/Book.php");
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
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>白石学園ポータルサイト</title>
  <link rel="stylesheet" href="Add.css" type="text/css">
</head>

<body>
  <div class="main">
    <!--ヘッダー-->
    <!-- Header.phpを呼び出す -->
    <?php include "../../../Test_DB/Header.php"; ?>
  </div><br>
  <!--パンくずリスト-->
  <ul class="breadcrumb">
    <li class="pan"><a href="../Home/TeacherHome.php">ホーム > 詳細</a></li>
  </ul>
  <div class="books"> <!-- 本の詳細 -->
    <div class="books"> <!-- 本の詳細 -->
      <img id="bookImage" src="AddBook.png" class="example1" style="vertical-align:top" alt="ファイルを選択"
        onclick="openFileInput()">
      <input type="file" id="fileInput" name="uploadedFile" class="hidden" onchange="updateImage(event)">
    </div>
    <div class="text">
      <p>
      <h2>著書名:</h2>
      </p>
      <p>
      <h2>著者名:</h2>
      </p>
      <p>
      <h2>出版社:</h2>
      </p>
      <p>
      <h2>持ち出し:</h2>
      </p>
      <p>
      <h2>ISBN:</h2>
      </p>
    </div>
  </div>
  <!--貸出、戻るボタン-->
  <div class="buttonContainer">
    <button id="rentButton" onclick="openDialog()">決定</button>
    <button id="returnButton" onclick="deleteDialog()">戻る</button>
  </div>
  <!--ダイアログボックスの表示-->
  <div id="dialog" class="dialog">
    <div class="dialog-content">
      <p class=AddText>この本を追加しますか？</p>
      <button id="yesButton" onclick=openComplete()>はい</button>
      <button id="noButton" onclick="closeDialog()">いいえ</button>
    </div>
  </div>
  <div id="dialog2">
    <div class="dialog2-content">
      <p class=AddButtonText>新しい本を追加しました</p>
      <button id="completeButton" onclick="redirectToHome()">OK</button>
    </div>
  </div>
  <div id="dialog3" class="dialog3">
    <div class="dialog-content">
      <p class=AddText>この情報を破棄しますか？</p>
      <button id="yesButton" onclick="deleteOkDialog()">はい</button>
      <button id="noButton" onclick="closeDialog2()">いいえ</button>
    </div>
  </div>
  <div id="dialog4">
    <div class="dialog2-content">
      <p class=AddButtonText>入力内容を破棄しました。</p>
      <button id="completeButton" onclick="redirectToHome()">OK</button>
    </div>
  </div>
  <!--<div id="dialog5">
    <div class="dialog2-content">
      <p class=AddButtonText>入力されていない情報があります</p>
      <button id="completeButton" onclick="redirectToHome()">OK</button>
    </div>
  </div>-->

  <script>


    // 画像をクリックしたときの処理
    document.getElementById('bookImage').addEventListener('click', function () {
      document.getElementById('fileInput').click();
    });

    // ファイルをアップロードした後の処理
  function updateImage(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const bookImage = document.getElementById('bookImage');
        bookImage.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }

    // フォーカスを別の要素に移動することでエクスプローラーが再度開かれるのを防止する
    document.body.focus();
  }

  // フォームデータを作成し、画像を非同期でアップロード
  function uploadImage() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    const formData = new FormData();
    
    // ファイル名を指定する（ここで任意のファイル名を設定）
    formData.append('uploadedFile', file, '36.jpg');

    // XMLHttpRequestを使って非同期でファイルをアップロード
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log(xhr.responseText); // アップロード結果のレスポンスを表示
        } else {
          console.error('アップロードに失敗しました。');
        }
      }
    };
    xhr.send(formData);
  }
  
    //はいいいえダイアログ
    function openDialog() {
      var dialog = document.getElementById("dialog");
      console.log(dialog);
      dialog.style.display = "block";
    }

    // はいボタンが押されたときの処理
    function openComplete() {
      var dialog = document.getElementById("dialog2");

      closeDialog();
      dialog.style.display = "block";

      // 画像を非同期でアップロード
      uploadImage();
    }

    //いいえ
    function closeDialog() {
      var dialog = document.getElementById("dialog");
      dialog.style.display = "none";
    }


    function closeDialog2() {
      var dialog = document.getElementById("dialog3");
      dialog.style.display = "none";
    }

    function deleteDialog() {
      var dialog = document.getElementById("dialog3");
      if (document.getElementById('fileInput').value) {
        dialog3.style.display = "block"; // 画像がアップロードされていればdeleteDialog()を実行
      } else {
        redirectToHome(); // 画像がアップロードされていなければredirectToHome()を実行
      }
    }

    function deleteOkDialog() {
      var dialog = document.getElementById("dialog4");
      closeDialog2();
      dialog.style.display = "block";
    }

    //ホームに戻る
    function redirectToHome() {
      // 別のページにリダイレクト（移動）
      window.location.href = "../Home/TeacherHome.php";
    }
  </script>
</body>

</html>