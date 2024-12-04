<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';

$userName = COMMON_INVALID_NAME;
$userPass = COMMON_INVALID_PASSWORD;
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
if (isset($post['password']) && isValidPassword($post['password'])) {
    $userPass = $post['password'];
}

// DBからユーザデータを取得する
$userData = dbGetUserDataByName($userName);
if ($userData === false) {
    // DBからデータを取得できない場合はログイン失敗
    $html = 'ユーザ名かパスワードが間違っています。';
} else {
    // ここに来た場合はユーザ名は一致
    if (password_verify($userPass, $userData['password'])) {
        // パスワードが一致した場合はログイン成功
        $countData = dbGetResultDataById($userData['user_id']);
        if ($countData === false) {
            $html = 'ユーザ名かパスワードが間違っています。';
        } else {
            // 各種データをセッション変数に保持する
            setSessionUserData(
                $userData['user_id'],
                $userData['name'],
                $countData['win_count'],
                $countData['loss_count']
            );
            // じゃんけんの手選択へリダイレクト
            header('Location: select.php');
            exit();
        }
    } else {
        // パスワードが一致しない場合はログイン失敗
        $html = 'ユーザ名かパスワードが間違っています。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインエラー</title>
</head>
<body>
    <h1>ログインエラー</h1>
    <?= $html ?><br>
    <br>
    <a href="user_login.php">戻る</a><br>
    <br>
    <a href=".">トップページへ</a><br>
</body>
</html>