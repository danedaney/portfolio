<!DOCUTYPE html>
<html>
<head>
  <meta lang = "UTF-8">
  <title>商品カートページ</title>
  <link rel="stylesheet" href="css/html5reset-1.6.1.css" type="text/css">
  <link rel="stylesheet" href="css/cart_view.css" type="text/css">
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
    <h1 class="title">ショッピングカート</h1>
    <?php if(count($rows) === 0) { ?>
    <p>商品はありません。</p>
    <?php } ?>
    <?php foreach ($err_msg as $value) { ?>
      <p><?php print $value; ?></p>
    <?php } ?>
    <?php foreach ($normal_msg as $value) { ?>
      <p><?php print $value; ?></p>
    <?php } ?>
    <div class="cart-list-title">
      <span class="cart-list-price">価格</span>
      <span class="cart-list-num">数量</span>
    </div>
    <ul class="cart-list">
      <li>
        <?php foreach ($rows as $read) { ?>
        <div class="cart-item">
          <img class="cart-item-img" src="<?php print IMG_DIR . $read['img'];?>">
          <span class="cart-item-name"><?php print $read['name'] ?></span>
          <form class="cart-item-del" action="cart.php" method="post">
            <input type="submit" value="削除">
            <input type="hidden" name="item_id" value="<?php print $read['item_id'] ?>">
            <input type="hidden" name="sql_kind" value="delete_cart">
          </form>
          <span class="cart-item-price">¥ <?php print number_format($read['price']) ?></span>
          <form class="form_select_amount" action="cart.php" method="post">
            <input type="text" class="cart-item-num2" name="amount" value="<?php print $read['amount'] ?>">個&nbsp;<input type="submit" value="変更する">
            <input type="hidden" name="item_id" value="<?php print $read['item_id'] ?>">
            <input type="hidden" name="sql_kind" value="change_cart">
          </form>
        </div>
        <?php } ?>
      </li>
    </ul>
    <div class="buy-sum-box">
      <span class="buy-sum-title">合計</span>
      <span class="buy-sum-price">¥<?php print number_format($total_price) ?></span>
    </div>
    <div>
      <form action="finish.php" method="post">
        <input class="buy-btn" type="submit" value="購入する">
      </form>
    </div>
  </div>
</body>
</html>