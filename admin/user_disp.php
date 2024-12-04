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
$userId = COMMON_INVALID_ID;
$html = '';

// GETデータを取得する
$get = h($_GET);
// 設定されていれば上書きする
if (isset($get['user_id']) && is_numeric($get['user_id'])) {
    $userId = intval($get['user_id']);
}

if ($userId == COMMON_INVALID_ID) {
    $html = 'ユーザ情報を参照できません。<br>';
} else {
    // ユーザデータを取得する
    $userData = dbGetUserCountDataById($userId);
    if ($userData === false) {
        $html = 'ユーザ情報を参照できません。<br>';
    } else {
        // ユーザ情報を表示する
        $html = <<< EOM
        ユーザID：{$userData['user_id']}<br>
        ユーザ名：{$userData['name']}<br>
        パスワード：{$userData['password']}<br>
        戦績ID：{$userData['result_id']}<br>
        勝ち数：{$userData['win_count']}<br>
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
    <a href="user_list.php">戻る</a>
</body>
</html>