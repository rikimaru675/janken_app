<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';

// 変数定義
$userName = COMMON_INVALID_NAME;
$password1 = COMMON_INVALID_PASSWORD;
$password2 = COMMON_INVALID_PASSWORD;
$errMsg = '';
$isInvalid = false;

// POSTデータを取得
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
if (isset($post['name']) && !empty($post['name'])) {
    $userName = $post['name'];
}
if (isset($post['password1']) && !empty($post['password1'])) {
    $password1 = $post['password1'];
}
if (isset($post['password2']) && !empty($post['password2'])) {
    $password2 = $post['password2'];
}

// ユーザ名をチェックする
if ($userName == COMMON_INVALID_NAME) {
    $errMsg .= 'ユーザ名が入力されていません。<br>';
    $isInvalid = true;
} else if (strlen($userName) > COMMON_NAME_MAX) {
    $errMsg .= sprintf('ユーザ名は最大%d文字です。<br>', COMMON_NAME_MAX);
    $isInvalid = true;
} else if (isValidName($userName) === false) {
    $errMsg .= 'ユーザ名に使える文字は、半角の英小文字・英大文字・数字・アンダースコア(_)です。<br>';
    $isInvalid = true;
} else {
    // 登録済みの名前かチェックする
    $userData = dbGetUserDataByName($userName);
    if ($userData === false) {
        // データを取得できなかったので未登録の名前
        // 何もしない
    } else {
        // データを取得できたので登録済みの名前
        $errMsg .= sprintf('%sは登録済みの名前です。<br>別の名前を入力してください。<br>', $userName);
        $isInvalid = true;
    }
}

// パスワードをチェックする
if ($password1 == COMMON_INVALID_PASSWORD) {
    $errMsg .= 'パスワードが入力されていません。<br>';
    $isInvalid = true;
} else if (strlen($password1) > COMMON_PASSWORD_MAX) {
    $errMsg .= sprintf('パスワードは最大%d文字です。<br>', COMMON_PASSWORD_MAX);
    $isInvalid = true;
} else if (isValidPassword($password1) === false) {
    $errMsg .= 'パスワードに使える文字は、半角の英小文字・英大文字・数字・記号です。<br>';
    $errMsg .= 'パスワードに使える記号は、!"#$%&\'()*+,-./:;<=>?@[]^_`{|}~です。<br>';
    $isInvalid = true;
} else {
    // 正常な場合は何もしない
}

// 再度入力したパスワードとチェックする
if ($password1 !== $password2) {
    $errMsg .= 'パスワードが一致しません。<br>';
    $isInvalid = true;
}

if ($isInvalid) {
    // エラーの場合の表示
    $html = <<< EOM
    $errMsg
    <br>
    <form>
        <input type="button" onclick="history.back()" value="戻る">
    </form>
    EOM;
} else {
    // ソースの表示で見えてしまうので、設定するパスワードはここでハッシュ化する
    $hashPassword = password_hash($password1, PASSWORD_DEFAULT);
    // csrfトークンの作成
    $csrfToken = getCsrfToken();
    // トークンをセッションへ保存
    setSessionCsrfToken($csrfToken);

    $html = <<< EOM
    ユーザを登録してもよろしいですか？<br>
    <br>
    ユーザ名：$userName<br>
    <form method="POST" action="user_register_exec.php">
        <input type="hidden" name="csrf_token" value="$csrfToken">
        <input type="hidden" name="name" value="$userName">
        <input type="hidden" name="password" value="$hashPassword">
        <br>
        <input type="button" onclick="history.back()" value="戻る">
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
    <title>ユーザ登録確認</title>
</head>
<body>
    <?= $html ?>
    <br>
    <a href=".">トップページへ</a>
</body>
</html>