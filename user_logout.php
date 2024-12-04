<?php
session_start();

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/session.php';

// セッションを破棄する
destroySession();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
</head>
<body>
    <h1>ログアウト</h1>
    ログアウトしました。<br>
    <br>
    <a href=".">トップページへ</a>
</body>
</html>