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
$isValid = false;

$get = h($_GET);
if (isset($get['user_id']) && is_numeric($get['user_id'])) {
    $userId = intval($get['user_id']);
}

if ($userId == COMMON_INVALID_ID) {
    $html = 'ユーザ情報を参照できません。<br>';
} else {
    // ユーザ情報を取得する
    $userData = dbGetUserCountDataById($userId);
    if ($userData === false) {
        $html = 'ユーザ情報を参照できません。<br>';
    } else {
        // 正常な場合は各種パラメータを取得する
        $userName = $userData['name'];
        $winCount  = $userData['win_count'];
        $lossCount = $userData['loss_count'];
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
            ユーザ名<br>
            <input type="text" name="name" value="$userName"><br>
            パスワードを入力してください。<br>
            <input type="password" name="password1"><br>
            パスワードをもう一度入力してください。<br>
            <input type="password" name="password2"><br>
            プレイ回数<br>
            勝ち数<br>
            <input type="text" name="win_count" value="$winCount"><br>
            負け数<br>
            <input type="text" name="loss_count" value="$lossCount"><br>
            <br>
            <input type="submit" value="OK">
        </form>
        EOM;
    }
    echo $html;
    ?>
    <br>
    <a href="user_list.php">戻る</a>
</body>
</html>