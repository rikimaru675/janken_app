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
$password = COMMON_INVALID_PASSWORD;
$winCount = 0;
$lossCount = 0;
$resetCount = false;
$html = '';

// POSTデータを取得する
$post = h($_POST);
// csrfトークンのチェック
if (!isset($post['csrf_token']) ||
    !compareSessionCsrfToken($post['csrf_token'])) {
    // csrfトークンエラー
    csrfTokenErrorHTML('.');
    exit();
} else {
    // csrfトークンを削除する
    deleteSessionCsrfToken();
}
// 設定されていれば上書きする
if (isset($post['user_id']) && is_numeric($post['user_id'])) {
    $userId = intval($post['user_id']);
}                
if (isset($post['name']) && isValidName($post['name'])) {
    $userName = $post['name'];
}
if (isset($post['password']) && isHashedPassword($post['password'])) {
    $password = $post['password'];
}
if (isset($post['reset_count']) && is_numeric($post['reset_count'])) {
    $resetCount = ($post['reset_count']) ? true : false;
}

if ($userId == COMMON_INVALID_ID ||
    $userName == COMMON_INVALID_NAME ||
    $password == COMMON_INVALID_PASSWORD) {
    // 各パラメータが設定されていない場合
    $html = '修正に失敗しました。<br>';
} else {
    if ($resetCount) {
        // ユーザ名とパスワードに加え、各種カウンタ値も修正する
        $result = dbEditUserResultData($userId, $userName, $password,
                                       $winCount, $lossCount);
    } else {
        // ユーザ名とパスワードのみ修正する
        $result = dbEditUserData($userId, $userName, $password);
    }
    if ($result) {
        $html = '修正しました。<br>';
    } else {
        $html = '修正に失敗しました。<br>';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ修正実行</title>
</head>
<body>
    <?= $html ?>
    <br>
    <a href="user_menu.php">ユーザメニューへ</a>
</body>
</html>