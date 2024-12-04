<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/session.php';

// ログイン済みかチェックする
if (!isUserLogin()) {
    noLoginHTML('user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザメニュー</title>
</head>
<body>
    <h1>ユーザメニュー</h1>
    <a href="user_disp.php">ユーザ情報参照</a><br>
    <br>
    <a href="user_edit.php">ユーザ設定変更</a><br>
    <br>
    <a href="user_cancel.php">解約</a><br>
    <br>
    <a href=".">トップページへ</a><br>
</body>
</html>