<?php
session_start();

require_once __DIR__ . '/../lib/common.php';
require_once __DIR__ . '/../lib/session.php';

// csrfトークンの作成
$csrfToken = getCsrfToken();
// トークンをセッションへ保存
setSessionCsrfToken($csrfToken);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
</head>
<body>
    <h1>管理者ログイン</h1><br>
    <form method="POST" action="admin_login_check.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        管理者名<br>
        <input type="text" name="name"><br>
        パスワード<br>
        <input type="password" name="password"><br>
        <br>
        <input type="submit" value="ログイン">
    </form>
</body>
</html>