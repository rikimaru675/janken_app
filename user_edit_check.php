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
$password1 = COMMON_INVALID_PASSWORD;
$password2 = COMMON_INVALID_PASSWORD;
$resetCount = false;
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
if (isset($post['user_id']) && is_numeric($post['user_id'])) {
    $userId = intval($post['user_id']);
}
if (isset($post['name']) && !empty($post['name'])) {
    $userName = $post['name'];
}
if (isset($post['password1']) && !empty($post['password1'])) {
    $password1 = $post['password1'];
}
if (isset($post['password2']) && !empty($post['password2'])) {
    $password2 = $post['password2'];
}
if (isset($post['reset_count'])) {
    $resetCount = true;
}

// ユーザIDをチェックする
if ($userId == COMMON_INVALID_ID) {
    $errMsg .= 'ユーザIDが設定されていません。<br>';
    $isInvalid = true;
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
    // 正常な場合は何もしない
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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ修正確認</title>
</head>
<body>
    <?php
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
        ユーザ情報を修正してもよろしいですか？<br>
        <br>
        ユーザ名：$userName<br>
        <form method="POST" action="user_edit_exec.php">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="hidden" name="user_id" value="$userId">
            <input type="hidden" name="name" value="$userName">
            <input type="hidden" name="password" value="$hashPassword">
            <input type="hidden" name="reset_count" value="$resetCount">
            <br>
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="OK">
        </form>
        EOM;
    }
    echo $html;
    ?>
</body>
</html>