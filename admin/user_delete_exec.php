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

if ($userId == COMMON_INVALID_ID) {
    // パラメータが設定されていない場合
    $html = 'ユーザの削除に失敗しました。';
} else {
    // DBのユーザ情報と関連する戦績情報を削除する
    $result = dbDelUserResultData($userId);
    if ($result) {
        $html = 'ユーザを削除しました。';
    } else {
        $html = 'ユーザの削除に失敗しました。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ削除実行</title>
</head>
<body>
    <?= $html ?><br>
    <br>
    <a href="user_list.php">戻る</a>
</body>
</html>