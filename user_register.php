<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/session.php';

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
    <title>ユーザ登録</title>
</head>
<body>
    <h1>ユーザ登録</h1>
    <form method="POST" action="user_register_check.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        ユーザ名<br>
        <input type="text" name="name"><br>
        パスワードを入力してください。<br>
        <input type="password" name="password1"><br>
        パスワードをもう一度入力してください。<br>
        <input type="password" name="password2"><br>
        <br>
        <input type="submit" value="OK">
    </form>
    <br>
    <a href=".">トップページへ</a>
</body>
</html>