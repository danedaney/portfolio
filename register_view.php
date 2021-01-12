<!DOCUTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/html5reset-1.6.1.css" type="text/css">
  <link rel="stylesheet" href="css/register_view.css" type="text/css">
  <title>Starting Select</title>
</head>
<body>
  <header>
    <div class="wrapper">
      <div class="logo">
        <a href="itemlist.php"><img src="img/logo.png"></a>
      </div>
    </div>  
  </header>
  <main>
    <div class="wrapper">
      <img id="background" src="img/background.jpg">
      <div class="container">
        <div class="form">
          <h2 class="heading">新規アカウント登録</h2>
          <form method="post" action="register.php">
            <p class="part">ユーザー名</p>
            <p class="part"><input type="text" name="user_name" placeholder="半角英数字(6文字以上)"></p>
            <p class="part">パスワード</p>
            <p class="part"><input type="password" name="password" placeholder="半角英数字(6文字以上)"></p>
            <p class="buttun"><input type="submit" value="新規登録する"></p>
          </form>
          <?php foreach ($err_msg as $value) { ?>
          <p class="msg"><?php print $value; ?></p>
          <?php } ?>
          <?php foreach ($normal_msg as $value) { ?>
          <p class="msg"><?php print $value; ?></p>
          <?php } ?>
          <p class="login"><a href="login.php">こちら</a>からログインページに移動する</p>
        </div>
      </div>
    <div>
  </main>      
</body>
</html>