<?php
//設定ファイル読み込み
require_once '../../include/starting_select/conf/const.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/common.php';
//関数ファイル読み込み
require_once '../../include/starting_select/model/admin_user_model.php';

//変数の初期化

//ログインユーザーか確認する
//ログインのチェック
session_start();
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $user_name = $_SESSION['user_name'];
} else {
// 非ログインの場合、ログインページへリダイレクト
  header('Location: login.php');
  exit;
}


try {
  //DB接続
  $dbh = get_db_connect();
  
  $rows = get_user_list($dbh);
  
  $rows = entity_assoc_array($rows);

} catch (Exception $e) {
  echo '失敗しました。理由:'.$e->getMessage();
}
//ユーザ管理ページの読み込み
include_once '../../include/starting_select/view/admin_user_view.php';
