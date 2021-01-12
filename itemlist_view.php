<!DOCUTYPE html>
<html>
<head>
  <meta lang = "UTF-8">
  <title>商品一覧ページ</title>
  <link rel="stylesheet" href="css/html5reset-1.6.1.css" type="text/css">
  <link rel="stylesheet" href="css/itemlist_view.css" type="text/css">
</head>
<body>
  <header>
    <div class="header_box">
      <a href="itemlist.php"><img class="logo" src="./img/logo.png"></a>
      <a class="logout" href="logout.php">ログアウト</a> 
      <a href="cart.php"><img class="cart" src="./img/cart.png"></a>
      <p class="name">ユーザー名 : <?php print $user_name?></p>
    </div>  
  </header>
  <div class="content">
    <?php foreach ($normal_msg as $value) { ?>
    <p><?php print $value; ?></p>
    <?php } ?>
    <ul class="item-list">
      <?php foreach($rows as $read) { ?>
      <?php if($read['status'] === '1') {?>
      <li>
        <div class="item">
          <form action="itemlist.php" method="post">
            <img class="item-img" src="./item_img/<?php print $read['img'];?>">
            <div class="item-info">
              <span class="item-name"><?php print $read['name'] ?></span>
              <span class="item-price">¥<?php print number_format($read['price']) ?></span>
            </div>
            <div class="item-subinfo">
              <span class="item-comment"><?php print $read['comment'] ?></span>
            </div>  
          <?php if($read['stock'] === '0') {?>
            <span id="soldout">売り切れ</span>
          <?php } else { ?>
            <input class="cart-btn" type="submit" value="カートに入れる">
          <?php } ?>
            <input type="hidden" name="item_id" value="<?php print $read['item_id'] ?>">
            <input type="hidden" name="sql_kind" value="insert_cart">
          </form>
        </div>
      </li>
      <?php } ?>
      <?php } ?>
    </ul>  
</body>
</html>