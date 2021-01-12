<!DOCUTYPE html>
<html lang="ja">
<head>
  <meta charset = "UTF-8">
  <link rel="stylesheet" href="css/admin_user.css" type="text/css">
  <title>ユーザ管理ページ</title>
</head>
<body>
  <h1>Starting Select管理ページ</h1>
  <div>
    <a href="admin.php" target="_blank">商品管理ページ</a>
  </div>
  <div>
    <a href="logout.php">ログアウト</a>
  </div>
  <h2>ユーザー情報一覧</h2>
  <table>
    <tr>
      <th>ユーザ名</th>
      <th>登録日</th>
    </tr>
    <?php foreach ($rows as $read) { ?>
    <tr>
      <td><?php print $read['user_name'];?></td>
      <td><?php print $read['create_datetime']?></td>
    </tr>
    <?php }?>
  </table>
</body>