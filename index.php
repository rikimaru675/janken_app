<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/session.php';

if (isUserLogin()) {
    // ログイン済みの場合
    $html = <<< EOM
    <a href="select.php">じゃんけん開始</a><br>
    <br>
    <a href="user_menu.php">ユーザ設定</a><br>
    <br>
    <a href="user_logout.php">ログアウト</a>
    EOM;
} else {
    // ログインしていない場合
    $html = <<< EOM
    <a href="user_login.php">ログインして始める</a><br>
    <br>
    <a href="select.php">ログインせずに始める</a><br>
    <br>
    <a href="user_register.php">ユーザ登録</a><br>
    EOM;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけんゲーム</title>
    <!-- <style>
        h1 {
            text-align: center;
        }
        a {
            display: block;
            width: fit-content;
            margin: 0 auto;
        }
    </style> -->
</head>
<body>
    <h1>じゃんけんゲーム</h1>
    <br>
    <?= $html ?>
</body>
</html>