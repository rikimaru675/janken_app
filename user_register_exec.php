<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';

// 変数定義
$userName = COMMON_INVALID_NAME;
$password = COMMON_INVALID_PASSWORD;
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
if (isset($post['name']) && isValidName($post['name'])) {
    $userName = $post['name'];
}
if (isset($post['password']) && isHashedPassword($post['password'])) {
    $password = $post['password'];
}

if ($userName == COMMON_INVALID_NAME ||
    $password == COMMON_INVALID_PASSWORD) {
    // 各パラメータが設定されていない場合
    $html = '登録に失敗しました。';
} else {
    // DBのユーザ情報を追加する
    $result = dbAddUserResultData($userName, $password);
    if ($result) {
        $html = '登録しました。';
    } else {
        $html = '登録に失敗しました。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録実行</title>
</head>
<body>
    <?= $html ?><br>
    <br>
    <a href=".">トップページへ</a>
</body>
</html>