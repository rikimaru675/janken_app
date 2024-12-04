<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';

// ログイン済みかチェックする
if (!isUserLogin()) {
    noLoginHTML('user_login.php');
    exit();
}

// 変数定義
$userId = COMMON_INVALID_ID;
$userName = COMMON_INVALID_NAME;
$html = '';
$isValid = false;

// セッションから各種データを取得する
$userId = getSessionUserId();
$userName = getSessionUserName();

if ($userId == COMMON_INVALID_ID ||
    $userName == COMMON_INVALID_NAME) {
    $html = 'ユーザ情報を参照できません。';
} else {
    // ユーザ情報を取得する
    $userData = dbGetUserDataById($userId);
    if ($userData === false) {
        $html = 'ユーザ情報を参照できません。';
    } else {
        $isValid = true;
    }
}

if ($isValid) {
    // csrfトークンの作成
    $csrfToken = getCsrfToken();
    // トークンをセッションへ保存
    setSessionCsrfToken($csrfToken);
    // 解約フォームを表示
    $html = <<< EOM
    このユーザを解約してもよろしいですか？<br>
    <br>
    ユーザ名：$userName<br>
    <form method="POST" action="user_cancel_exec.php">
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
    <title>解約</title>
</head>
<body>
    <h1>解約</h1>
    <?= $html ?><br>
    <br>
    <a href="user_menu.php">ユーザメニューへ</a>
</body>
</html>