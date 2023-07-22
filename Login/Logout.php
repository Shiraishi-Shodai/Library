<!DOCTYPE html>
<html>
<head>
<title>ログアウト</title>
<script>
function countdown() {
  var seconds = 5; // カウントダウンの秒数
  var countdownElement = document.getElementById("countdown");

  var countdownInterval = setInterval(function() {
    seconds--;
    countdownElement.innerHTML = seconds;

    if (seconds <= 0) {
      clearInterval(countdownInterval);
      window.location.href = "Login.php"; 
    }
  }, 1000);
}
</script>
</head>
<body onload="countdown()">
<h1>
<font size='5'>ログアウトしました</font>
</h1>
<p><span id="countdown">5</span>秒後に自動でログインページに戻ります</p>
<p><a href='Login.php'>ログインページ</a></p>
</body>
</html>
