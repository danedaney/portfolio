<?php
/**
*画像のアップロード
*@return param result['filename' => '', 'err_msg' => array()] 
*/
function new_img_filename() {
  $result = ['filename' => '', 'err_msg' => array()];
  if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
    $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
    if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'PNG' || $extension === 'png') {
       $result['filename']=sha1(uniqid(mt_rand(), true)). '.' . $extension;
      if (is_file('./item_img/'.$result['filename']) !== TRUE) {
        if (move_uploaded_file($_FILES['new_img']['tmp_name'], './item_img/' . $result['filename']) !== TRUE) {
          $result['err_msg'][] = 'ファイルアップロードに失敗しました';
        }
      } else {
        $result['err_msg'][] = 'ファイルアップロードに失敗しました。';
      }
    } else {
      $result['err_msg'][] = 'ファイル形式が異なります。画像ファイルはJPEG、PNGのみ利用可能です。';
    }
  } else {
    $result['err_msg'][] = 'ファイルを選択してください';
  }
  return $result;
}

/**
*エラーチェック
*@param form送信データ（それぞれの変数は以下記載） 
*@return $err_msg
*/

//$name
function validation_name($str) {
  $err_msg = [];
  $pattern = '/(\s|　)+/';
  
  if($str === ''){
    $err_msg[] = '商品名を入力してください';
  }elseif(preg_match ($pattern, $str) === 1) {
    $err_msg[] = '商品名を入力してください';
  }
  return $err_msg;
}

//$price  
function validation_price($int) {
  $err_msg = [];
  $pattern = '/^[0-9]*$/'; 
  if($int === '') {
    $err_msg[] = '値段を入力してください';
  }elseif(preg_match ($pattern, $int) !== 1) {
    $err_msg[] = '値段は半角数字を入力してください';
  }
  return $err_msg;
}

//$stock  
function validation_stock($int) {
  $err_msg = [];
  $pattern = '/^[0-9]*$/'; 
  
  if($int === '') {
    $err_msg[] = '個数を入力してください';
  }elseif(preg_match ($pattern, $int) !== 1) {
    $err_msg[] = '個数は半角数字を入力してください';
  }
  return $err_msg;
}

//$comment
function validation_comment($str) {
  $err_msg = [];
  $pattern = '/(\s|　)+/';
  
  if($str === ''){
    $err_msg[] = '推しポイントを入力してください';
  }elseif(preg_match ($pattern, $str) === 1) {
    $err_msg[] = '推しポイントを入力してください';
  }
  return $err_msg;
}

//$status
function validation_status($int) {
  $err_msg = [];
  $pattern = '/^[0-1]{1}$/';
  
  if(preg_match ($pattern, $int) !== 1) {
    $err_msg[] = '不正な処理です';
  }
  return $err_msg;
}

/**
*item_masterにinsertする
*@param $drink_id...
*@returnなし 
*/
function insert_item_master($dbh, $name, $price, $comment, $new_img_filename, $status, $now_date) {
  try {
    $sql = 'insert into ec_item_master(name, price, comment, img, status, create_datetime, update_datetime)
            values(?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $name,    PDO::PARAM_STR); 
    $stmt->bindValue(2, $price, PDO::PARAM_INT);
    $stmt->bindValue(3, $comment, PDO::PARAM_STR);
    $stmt->bindValue(4, $new_img_filename,  PDO::PARAM_STR);
    $stmt->bindValue(5, $status,    PDO::PARAM_INT); 
    $stmt->bindValue(6, $now_date, PDO::PARAM_STR);
    $stmt->bindValue(7, $now_date,  PDO::PARAM_STR);
    $stmt->execute();
  } catch (PDOException $e) {
    throw $e;
  }
}

/**
*item_stockにinsertする
*@param $drink_id...
*@return $normal_msg
*/
function insert_item_stock($dbh, $item_id, $stock, $now_date) {
  try {
    $sql = 'insert into ec_item_stock(item_id, stock, create_datetime, update_datetime)
            values(?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $item_id,  PDO::PARAM_STR); 
    $stmt->bindValue(2, $stock,     PDO::PARAM_INT);
    $stmt->bindValue(3, $now_date,  PDO::PARAM_STR);
    $stmt->bindValue(4, $now_date,  PDO::PARAM_STR);
    $stmt->execute();
    $normal_msg[] = '商品を登録しました';
  } catch(PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}  

/**
*drink_stockを書き換える
*@param $drink_id...
*@returnなし 
*/
function update_item_stock($dbh, $item_id, $stock, $now_date) {
  try {
    $sql  ='update ec_item_stock SET stock=?, update_datetime=? WHERE item_id=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $stock,  PDO::PARAM_INT);
    $stmt->bindvalue(2, $now_date, PDO::PARAM_STR);
    $stmt->bindvalue(3, $item_id, PDO::PARAM_INT);
    $stmt->execute();
    $normal_msg[] = '在庫を変更しました';
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}

/**
*item_stockを書き換える
*@param $item_id...
*@return normal_msg 
*/
function update_item_master($dbh, $item_id, $status, $now_date) {
  try {
    $sql  ='update ec_item_master SET status=?, update_datetime=? WHERE item_id=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $status,  PDO::PARAM_INT);
    $stmt->bindvalue(2, $now_date, PDO::PARAM_STR);
    $stmt->bindvalue(3, $item_id, PDO::PARAM_INT);
    $stmt->execute();
    $normal_msg[] = 'ステータスを変更しました';
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}

//item_masterで指定のitem_idレコードを削除する
function delete_item_master($dbh, $item_id){
  try {
    $sql = 'DELETE 
            FROM ec_item_master
            WHERE item_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $item_id,  PDO::PARAM_INT);
    $stmt->execute();
  } catch (PDOException $e) {
    throw $e;
  }  
}

//item_stockで指定のitem_idレコードを削除する
function delete_item_stock($dbh, $item_id){
  $normal_msg[] = '';
  
  try {
    $sql = 'DELETE 
            FROM ec_item_stock
            WHERE item_id = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $item_id,  PDO::PARAM_INT);
    $stmt->execute();
    $normal_msg[] = '商品を削除しました';
  } catch (PDOException $e) {
    throw $e;
  }
  return $normal_msg;
}

/**
* 商品の一覧を取得する
* @param obj $dbh DBハンドル
* @return array 商品一覧配列データ
*/
function get_post_table_list($dbh) {
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

?>