<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
require_once '../../include/starting_select/model/register_model.php';

//変数の初期化
$user_name = '';
$password = '';
$err_msg = array();
$normal_msg = array();
$members = array();
$now_date = date('Y-m-d H:i:s');

try {
  $dbh = get_db_connect();
  
  $request_method = post_request_method();
  
  if($request_method === 'POST') {
    $user_name = get_post_data('user_name');
    $password = get_post_data('password');
    
    //validation
    $err_msg = array_merge(validation_password($password), $err_msg);
    $err_msg = array_merge(validation_user_name($user_name), $err_msg);
    
    if (count($err_msg) === 0) {
      
      $dbh->beginTransaction();
      try {
        $members = get_user_list($dbh, $user_name);
      
        if (count($members) === 0) {
          $normal_msg = insert_user($dbh, $user_name, $password, $now_date);
          $dbh->commit();
        } else {
          $err_msg[] = '同じユーザー名が既に登録されています';
        }
      } catch (PDOException $e) {
        $dbh->rollback();
        throw $e;
      }
    }
  }  
} catch (Exception $e) {
  echo '接続できませんでした。理由:'.$e->getMessage();
}

//購入ページファイル読み込み
include_once '../../include/starting_select/view/register_view.php';

?>