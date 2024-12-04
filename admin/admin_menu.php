<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/../lib/common.php';
require_once __DIR__ . '/../lib/session.php';

// 管理者ログイン済みかチェックする
if (!isAdminLogin()) {
    noLoginHTML('admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者メニュー</title>
</head>
<body>
    <h1>管理者メニュー</h1>
    <a href="admin_list.php">管理者管理</a><br>
    <br>
    <a href="user_list.php">ユーザ管理</a><br>
    <br>
    <a href="admin_logout.php">ログアウト</a><br>
</body>
</html>