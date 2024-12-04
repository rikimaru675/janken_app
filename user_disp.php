<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';

// ユーザログイン済みかチェックする
if (!isUserLogin()) {
    noLoginHTML('user_login.php');
    exit();
}

// 変数定義
$userId = COMMON_INVALID_ID;
$html = '';

// セッションからユーザIDを取得する
$userId = getSessionUserId();

if ($userId == COMMON_INVALID_ID) {
    $html = 'ユーザ情報を参照できません。<br>';
} else {
    // DBからユーザデータを取得する
    $userData = dbGetUserCountDataById($userId);
    if ($userData === false) {
        $html = 'ユーザ情報を参照できません。<br>';
    } else {
        // ユーザ情報を表示する
        $html = <<< EOM
        ユーザ名：{$userData['name']}<br>
        <br>
        勝ち数：{$userData['win_count']}<br>
        <br>
        負け数：{$userData['loss_count']}<br>
        EOM;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ情報参照</title>
</head>
<body>
    <h1>ユーザ情報参照</h1>
    <?= $html ?>
    <br>
    <a href="user_menu.php">ユーザメニューへ</a>
</body>
</html>