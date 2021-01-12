<?php
/**
* 商品の一覧を取得する
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function get_item_list($dbh) {
  $rows = array();
  
  try { 
    // SQL生成
    $sql = 'SELECT eim.item_id, eim.name, eim.price, eim.comment, eim.img, eis.stock, eim.status
            FROM ec_item_master AS eim
            JOIN ec_item_stock AS eis ON eim.item_id = eis.item_id';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();
    
  } catch (PDOException $e) {
    throw $e;
  } 
  return $rows;
}

/**
* カートの一覧にuser_idとitem_idするレコードがあるかをチェック
* @param obj $dbh, $user_id, $item_id
* @return array ec_cart配列データ
*/

function get_ec_cart_list($dbh, $item_id, $user_id) {
  $carts = array();
  
  try { 
    $sql = 'SELECT *
            FROM ec_cart
            WHERE item_id = ? AND user_id = ?';
            
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $carts = $stmt->fetchAll();
  } catch (PDOException $e) {
    throw $e;
  } 
  return $carts;
}

/**
*ec_cart_amountを書き換える
*@param obj $dbh, $user_id, $item_id 
*@return normal_msg 
*/
function update_cart_amount($dbh, $user_id, $item_id, $now_date) {
  $normal_msg = array();
  
  try {
    $sql  ='update ec_cart SET amount = amount + 1, update_datetime=? WHERE user_id=? AND item_id=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $now_date, PDO::PARAM_STR);
    $stmt->bindvalue(2, $user_id,  PDO::PARAM_INT);
    $stmt->bindvalue(3, $item_id,  PDO::PARAM_INT);
    $stmt->execute();
    $normal_msg[] = 'カートに登録しました';
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}

/**
*ec_cart_amountを書き換える
*@param obj $dbh, $user_id, $item_id 
*@return normal_msg 
*/
function insert_cart($dbh, $user_id, $item_id, $now_date) {
    $normal_msg = array();
    
    try {
    $sql = 'insert into ec_cart(user_id, item_id, amount, create_datetime, update_datetime)
            values(?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $user_id,   PDO::PARAM_INT); 
    $stmt->bindValue(2, $item_id,   PDO::PARAM_INT);
    $stmt->bindValue(3, 1,          PDO::PARAM_INT);
    $stmt->bindValue(4, $now_date,  PDO::PARAM_STR);
    $stmt->bindValue(5, $now_date,  PDO::PARAM_STR);
    $stmt->execute();
    $normal_msg[] = '商品を登録しました';
  } catch(PDOException $e) {
    throw $e;
  }
  return $normal_msg;
} 
