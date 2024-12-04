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
$userName = COMMON_INVALID_NAME;
$password = COMMON_INVALID_PASSWORD;
$winCount  = COMMON_INVALID_COUNT;
$lossCount = COMMON_INVALID_COUNT;
$html = '';

// POSTデータを取得する
$post = h($_POST);
// csrfトークンのチェック
if (!isset($post['csrf_token']) ||
    !compareSessionCsrfToken($post['csrf_token'])) {
    // csrfトークンエラー
    csrfTokenErrorHTML('admin_login.php');
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
if (isset($post['win_count']) && is_numeric($post['win_count'])) {
    $winCount = intval($post['win_count']);
}
if (isset($post['loss_count']) && is_numeric($post['loss_count'])) {
    $lossCount = intval($post['loss_count']);
}

if ($userId == COMMON_INVALID_ID ||
    $userName == COMMON_INVALID_NAME ||
    $password == COMMON_INVALID_PASSWORD ||
    $winCount == COMMON_INVALID_COUNT ||
    $lossCount == COMMON_INVALID_COUNT) {
    // 各パラメータが設定されていない場合
    $html = '修正に失敗しました。<br>';
} else {
    // DBのユーザ情報を修正する
    $result = dbEditUserResultData($userId, $userName, $password,
                                   $winCount, $lossCount);
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
    <a href="user_list.php">戻る</a>
</body>
</html>