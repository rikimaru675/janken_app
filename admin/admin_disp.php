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

// GETデータを取得する
$get = h($_GET);
// 設定されていれば上書きする
if (isset($get['admin_id']) && is_numeric($get['admin_id'])) {
    $adminId = intval($get['admin_id']);
}

if ($adminId == COMMON_INVALID_ID) {
    $html = '管理者情報を参照できません。<br>';
} else {
    // DBから管理者データを取得する
    $adminData = dbGetAdminDataById($adminId);
    if ($adminData === false) {
        $html = '管理者情報を参照できません。<br>';
    } else {
        // 管理者情報を表示する
        $html = <<< EOM
        管理者名：{$adminData['name']}<br>
        <br>
        パスワード：{$adminData['password']}<br>
        EOM;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者情報参照</title>
</head>
<body>
    <h1>管理者情報参照</h1>
    <?= $html ?>
    <br>
    <a href="admin_list.php">戻る</a>
</body>
</html>