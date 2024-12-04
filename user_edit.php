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
$isValid = false;

// セッションからユーザIDを取得する
$userId = getSessionUserId();

if ($userId == COMMON_INVALID_ID) {
    $html = 'ユーザ情報を参照できません。<br>';
} else {
    // ユーザ情報を取得する
    $userData = dbGetUserDataById($userId);
    if ($userData === false) {
        $html = 'ユーザ情報を参照できません。<br>';
    } else {
        // 正常な場合は各種パラメータを取得する
        $userName = $userData['name'];
        $isValid = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ情報修正</title>
</head>
<body>
    <h1>ユーザ情報修正</h1>
    <?php
    if ($isValid) {
        // csrfトークンの作成
        $csrfToken = getCsrfToken();
        // トークンをセッションへ保存
        setSessionCsrfToken($csrfToken);
        // 情報修正フォームを表示
        $html = <<< EOM
        <form method="POST" action="user_edit_check.php">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="hidden" name="user_id" value="$userId">
            <input type="hidden" name="name" value="$userName"><br>
            ユーザ名<br>
            $userName<br>
            <br>
            パスワードを入力してください。<br>
            <input type="password" name="password1"><br>
            パスワードをもう一度入力してください。<br>
            <input type="password" name="password2"><br>
            <br>
            <input type="checkbox" id="reset_count" name="reset_count">
            <label for="reset_count">勝敗数のリセット</label><br>
            <br>
            <input type="submit" value="OK">
        </form>
        EOM;
    }
    echo $html;
    ?>
    <br>
    <a href="user_menu.php">ユーザメニューへ</a>
</body>
</html>