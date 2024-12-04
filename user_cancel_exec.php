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
$html = '';
$hrefUrl = '';
$hrefMsg = '';

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

if ($userId == COMMON_INVALID_ID) {
    // パラメータが設定されていない場合
    $html = '解約に失敗しました。';
    $hrefUrl = 'user_menu.php';
    $hrefMsg = 'ユーザメニューへ';
} else {
    // DBのユーザ情報と関連する戦績情報を削除する
    $result = dbDelUserResultData($userId);
    if ($result) {
        $html = '解約しました。';
        $hrefUrl = '.';
        $hrefMsg = 'トップページへ';
        // セッションを破棄する
        destroySession();
    } else {
        $html = '解約に失敗しました。';
        $hrefUrl = 'user_menu.php';
        $hrefMsg = 'ユーザメニューへ';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>解約実行</title>
</head>
<body>
    <?= $html ?><br>
    <br>
    <a href="<?= $hrefUrl ?>"><?= $hrefMsg ?></a>
</body>
</html>