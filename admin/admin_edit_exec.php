<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/../lib/common.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/session.php';

// 管理者ログイン済みかチェックする
if (!isAdminLogin()) {
    noLoginHTML('admin_login.php');
    exit();
}

// 変数定義
$adminId = COMMON_INVALID_ID;
$name = COMMON_INVALID_NAME;
$password = COMMON_INVALID_PASSWORD;
$msg = '';

// POSTデータを取得する
$post = h($_POST);
// csrfトークンのチェック
if (!isset($post['csrf_token']) ||
    !compareSessionCsrfToken($post['csrf_token'])) {
    // csrfトークンエラー
    csrfTokenErrorHTML('admin_login.php');
    exit();
} else {
    // csrfトークンを削除する
    deleteSessionCsrfToken();
}
// 設定されていれば上書きする
if (isset($post['admin_id']) && is_numeric($post['admin_id'])) {
    $adminId = intval($post['admin_id']);
}
if (isset($post['name']) && isValidName($post['name'])) {
    $name = $post['name'];
}
if (isset($post['password']) && isHashedPassword($post['password'])) {
    $password = $post['password'];
}

if ($adminId == COMMON_INVALID_ID ||
    $name == COMMON_INVALID_NAME ||
    $password == COMMON_INVALID_PASSWORD) {
    // 各パラメータが設定されていない場合
    $msg = '修正に失敗しました。<br>';
} else {
    // DBの管理者情報を修正する
    $result = dbEditAdminData($adminId, $name, $password);
    if ($result) {
        $msg = '修正しました。<br>';
    } else {
        $msg = '修正に失敗しました。<br>';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者修正実行</title>
</head>
<body>
    <?= $msg ?>
    <br>
    <a href="admin_list.php">戻る</a>
</body>
</html>