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
$adminId = COMMON_INVALID_ID;
$html = '';
$isValid = false;

// GETデータを取得する
$get = h($_GET);
// 設定されていれば上書きする
if (isset($get['admin_id']) && is_numeric($get['admin_id'])) {
    $adminId = intval($get['admin_id']);
}

if ($adminId == COMMON_INVALID_ID) {
    $html = '管理者情報を参照できません。<br>';
} else {
    // 管理者情報を取得する
    $adminData = dbGetAdminDataById($adminId);
    if ($adminData === false) {
        $html = '管理者情報を参照できません。<br>';
    } else {
        $name = $adminData['name'];
        $isValid = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者情報修正</title>
</head>
<body>
    <h1>管理者情報修正</h1>
    <?php
    if ($isValid) {
        // csrfトークンの作成
        $csrfToken = getCsrfToken();
        // トークンをセッションへ保存
        setSessionCsrfToken($csrfToken);
        // 情報修正フォームを表示
        $html = <<< EOM
        <form method="POST" action="admin_edit_check.php">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="hidden" name="admin_id" value="$adminId">
            管理者名<br>
            <input type="text" name="name" value="$name"><br>
            パスワードを入力してください。<br>
            <input type="password" name="password1"><br>
            パスワードをもう一度入力してください。<br>
            <input type="password" name="password2"><br>
            <br>
            <input type="submit" value="OK">
        </form>
        EOM;
    }
    echo $html;
    ?>
    <br>
    <a href="admin_list.php">戻る</a>
</body>
</html>