<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/../lib/common.php';
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/session.php';

$adminName = COMMON_INVALID_NAME;
$adminPass = COMMON_INVALID_PASSWORD;

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
if (isset($post['name']) && isValidName($post['name'])) {
    $adminName = $post['name'];
}
if (isset($post['password']) && isValidPassword($post['password'])) {
    $adminPass = $post['password'];
}

// DBから管理者データを取得する
$adminData = dbGetAdminDataByName($adminName);
if ($adminData === false) {
    // DBからデータを取得できない場合はログイン失敗
    $msg = '管理者名かパスワードが間違っています。';
} else {
    if (password_verify($adminPass, $adminData['password'])) {
        // パスワードが一致した場合はログイン成功

        // 各種データをセッション変数に保持する
        setSessionAdminData($adminData['admin_id'], $adminData['name']);
        // 管理者メニューへリダイレクト
        header('Location: admin_menu.php');
        exit();
    } else {
        // パスワードが一致しない場合はログイン失敗
        $msg = '管理者名かパスワードが間違っています。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインエラー</title>
</head>
<body>
    <h1>ログインエラー</h1>
    <?= $msg ?><br>
    <br>
    <a href="admin_login.php">戻る</a>
</body>
</html>