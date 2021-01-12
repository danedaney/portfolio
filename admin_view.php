<!DOCUTYPE html>
<html>
<head>
  <meta lang = "UTF-8">
  <title>Starting Select管理ページ</title>
  <style>
    h1 {
      border-bottom: solid 1px;
    }
    .container {
      border-bottom: solid 1px;
    }
    table {
      width: 960px;
      border-collapse: collapse;
    }
    table, tr, th, td {
      border: solid 1px;
      padding: 10px;
      text-align: center;
    }
    #img_size {
      width: 250px;
      height: 250px; 
    }
    #img_size img {
      width: 100%;
      height: 100%;
    }  
    .gray {
      background-color: gray;
    }
  </style>
</head>
<body>
<?php foreach ($err_msg as $value) { ?>
  <p><?php print $value; ?></p>
<?php } ?>
<?php foreach ($normal_msg as $value) { ?>
  <p><?php print $value; ?></p>
<?php } ?>
  <h1>Starting Select管理ページ</h1>
  <div>
    <a href="admin_user.php" target="_blank">ユーザー管理ページ</a>
  </div>
  <div>
    <a href="logout.php">ログアウト</a>
  </div>
  <div class = "container">
    <h2>商品の登録</h2>
    <form method = "post" enctype="multipart/form-data">
      <input type="hidden" name="sql_kind" value="insert">
      <p>商品名:<input type="text" name="name"></p>
      <p>値段:<input type="text" name="price"></p>
      <p>個数:<input type="text" name="stock"></p>
      <!--textareaに改修する-->
      <p>推しポイント:<input type="text" name="comment"></p> 
      <p>商品画像:<input type="file" name="new_img"></p>
      <p>
        <select name="private_public" style="width:100;">
          <option value="0">非公開</option>
          <option value="1">公開</option>
        </select>
      </p>
      <p><input type="submit" value="商品を登録する"></p>
    </form>
  </div>
  <h2>商品情報の一覧と変更</h2>
  <table>
    <tr>
      <th id="img_size_width">商品画像</th>
      <th>商品名</th>
      <th>価格</th>
      <th>推しポイント</th>
      <th>在庫数</th>
      <th>ステータス</th>
      <th>操作</th>
    </tr>
    <?php foreach ($rows as $read) { ?>
    <?php if($read['status'] === '0' ) { ?>
    <tr class = "gray">
    <?php } else { ?>
    <tr><?php } ?>
      <td id="img_size"><img class="img" src="<?php print IMG_DIR . $read['img'];?>"></td>
      <td><?php print $read['name'];?></td>
      <td><?php print number_format($read['price'])."円"?></td>
      <td><?php print $read['comment']?></td>
      <td>
        <form method = "post">
          <input type="hidden" name="sql_kind" value="update_stock">
          <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
          <input type="text" name="stock" value="<?php print $read['stock']?>" style="width:75;">個
          <input type="submit" value="変更">
        </form>
      </td>
      <td>
        <form method = "post">
          <input type="hidden" name="sql_kind" value="update_private_public">
          <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
          <?php if($read['status'] === '0' ) { ?>
          <input type="hidden" name="private_public" value="1">
          <input type="submit" value="非公開→公開">
          <?php } else { ?>
          <input type="hidden" name="private_public" value="0">
          <input type="submit" value="公開→非公開">
          <?php }?>
        </form>
      </td>
      <td>
        <form method = "post">
          <input type="hidden" name="sql_kind" value="delete">
          <input type="hidden" name="item_id" value="<?php print $read['item_id']?>">
          <input type="submit" value="削除">
        </form>
      </td>  
    </tr>
    <?php } ?>
  </table>    
</body>
</html>