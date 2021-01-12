<!DOCUTYPE html>
<html>
<head>
  <meta lang = "UTF-8">
  <title>商品カートページ</title>
  <link rel="stylesheet" href="css/html5reset-1.6.1.css" type="text/css">
  <link rel="stylesheet" href="css/finish_view.css" type="text/css">
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
    <?php if(count($rows) === 0) { ?>
    <p>商品はありません。</p>
    <?php } else { ?>
    <div class="finish-msg">ご購入ありがとうございました。</div>
    <div class="cart-list-title">
      <span class="cart-list-price">価格</span>
      <span class="cart-list-num">数量</span>
    </div>
      <?php foreach ($rows as $read) { ?>
      <ul class="cart-list">
        <li>
          <div class="cart-item">
            <img class="cart-item-img" src="<?php print IMG_DIR . $read['img'];?>">
            <span class="cart-item-name"><?php print $read['name'] ?></span>
            <span class="cart-item-price">¥ <?php print number_format($read['price']) ?></span>
            <span class="finish-item-price"><?php print $read['amount'] ?></span>
          </div>
        </li>
      </ul>
      <?php } ?>
    <div class="buy-sum-box">
      <span class="buy-sum-title">合計</span>
      <span class="buy-sum-price">¥ <?php print number_format($total_price) ?></span>
    </div>
    <?php } ?>
  </div>
</body>
</html>
  