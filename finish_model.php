<?php

//item_masterとec_cartをitem_idで結合し、カラムがuser_idを取り出す
function get_item_list($dbh, $user_id){
   try {
    $sql = 'select eim.name, eim.item_id, eim.img, eim.price, ec.amount, eis.stock, eim.price * ec.amount AS total_price
            from ec_item_master AS eim join ec_cart AS ec on eim.item_id = ec.item_id join ec_item_stock AS eis on eim.item_id = eis.item_id
            WHERE ec.user_id= ? ';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id,  PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();
  } catch(PDOException $e) {
    throw $e; 
  }
  return $rows;
}

//$rowsの price*amount カラムを合計する
function total_price($rows) {
  $total_price = 0;
  foreach($rows as $value) {
    $total_price += $value['total_price'];
  }
  return $total_price;
}

//購入商品の在庫数を購入数に応じて減らす
function update_item_stock($dbh, $item_id, $amount, $now_date) {
    
    try {
    $sql = 'update ec_item_stock SET stock = stock - ?, update_datetime=? WHERE item_id=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $amount,  PDO::PARAM_INT);
    $stmt->bindValue(2, $now_date,  PDO::PARAM_STR);
    $stmt->bindValue(3, $item_id,  PDO::PARAM_INT);
    $stmt->execute();
    
  } catch (PDOException $e) {
    throw $e;
  }
}

//購入商品の在庫数を購入数に応じて減らす
function delete_cart($dbh, $user_id) {
    
    try {
    $sql = 'DELETE 
            FROM ec_cart
            WHERE user_id = ? ';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id,  PDO::PARAM_INT);
    $stmt->execute();
    
  } catch (PDOException $e) {
    throw $e;
  }
}

?>