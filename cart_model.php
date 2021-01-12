<?php

//$amountを変更する場合、正の自然数のみ
function validation_amount($int) {
  $err_msg = [];
  $pattern = '/^[1-9][0-9]*$/'; 
  
  if($int === '') {
    $err_msg[] = '個数を入力してください';
  } elseif(preg_match ($pattern, $int) !== 1) {
    $err_msg[] = '個数は正の整数を入力してください';
  }
  return $err_msg;
}


//item_masterで指定のitem_idレコードを削除する
function delete_cart($dbh, $user_id, $item_id){
  $normal_msg = [];
  
  try {
    $sql = 'DELETE 
            FROM ec_cart
            WHERE user_id = ? AND item_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id,  PDO::PARAM_INT);
    $stmt->bindValue(2, $item_id,  PDO::PARAM_INT);
    $stmt->execute();
    
    $normal_msg[] = '削除しました';
    
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}

//item_masterで指定のitem_idレコードを削除する
function update_cart($dbh, $user_id, $item_id, $amount, $now_date){
  $normal_msg = [];
  
  try {
    $sql = 'update ec_cart SET amount=?, update_datetime=? WHERE user_id=? AND item_id=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $amount,  PDO::PARAM_INT);
    $stmt->bindValue(2, $now_date,  PDO::PARAM_STR);
    $stmt->bindValue(3, $user_id,  PDO::PARAM_INT);
    $stmt->bindValue(4, $item_id,  PDO::PARAM_INT);
    $stmt->execute();
    
    $normal_msg[] = '更新しました';
    
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}

//item_masterとec_cartをitem_idで結合し、カラムがuser_idを取り出す
function get_item_list($dbh, $user_id){
   try {
    $sql = 'select eim.name, eim.item_id, eim.img, eim.price, ec.amount, eim.price * ec.amount AS total_price
            from ec_item_master AS eim join ec_cart AS ec on eim.item_id = ec.item_id
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

?>