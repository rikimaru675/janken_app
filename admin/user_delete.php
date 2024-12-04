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
$isValid = false;

$get = h($_GET);
if (isset($get['user_id']) && is_numeric($get['user_id'])) {
    $userId = intval($get['user_id']);
}

if ($userId == COMMON_INVALID_ID) {
    $html = 'ユーザ情報を参照できません。<br>';
} else {
    // ユーザ情報を取得する
    $userData = dbGetUserDataById($userId);
    if ($userData === false) {
        $html = 'ユーザ情報を参照できません。<br>';
    } else {
        $userName = $userData['name'];
        $isValid = true;
    }
}

if ($isValid) {
    // csrfトークンの作成
    $csrfToken = getCsrfToken();
    // トークンをセッションへ保存
    setSessionCsrfToken($csrfToken);
    // ユーザ削除フォームを表示
    $html = <<< EOM
    このユーザを削除してもよろしいですか？<br>
    <br>
    ユーザ名：$userName<br>
    <form method="POST" action="user_delete_exec.php">
        <input type="hidden" name="csrf_token" value="$csrfToken">
        <input type="hidden" name="user_id" value="$userId">
        <input type="submit" value="OK">
    </form>
    EOM;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ削除</title>
</head>
<body>
    <h1>ユーザ削除</h1>
    <?= $html ?>
    <br>
    <a href="user_list.php">戻る</a>
</body>
</html>