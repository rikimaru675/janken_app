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
$name = COMMON_INVALID_NAME;
$password1 = COMMON_INVALID_PASSWORD;
$password2 = COMMON_INVALID_PASSWORD;
$isInvalid = false;

// POSTデータを取得
$post = h($_POST);
// csrfトークンのチェック
if (!isset($post['csrf_token']) ||
    !compareSessionCsrfToken($post['csrf_token'])) {
    // csrfトークンエラー
    csrfTokenErrorHTML('../admin_login/admin_login.php');
    exit();
} else {
    // csrfトークンを削除する
    deleteSessionCsrfToken();
}
// 設定されていれば上書きする
if (isset($post['admin_id']) && is_numeric($post['admin_id'])) {
    $adminId = intval($post['admin_id']);
}
if (isset($post['name']) && !empty($post['name'])) {
    $name = $post['name'];
}
if (isset($post['password1']) && !empty($post['password1'])) {
    $password1 = $post['password1'];
}
if (isset($post['password2']) && !empty($post['password2'])) {
    $password2 = $post['password2'];
}

// 管理者IDをチェックする
if ($adminId == COMMON_INVALID_ID) {
    echo '管理者IDが設定されていません。<br>';
    $isInvalid = true;
}

// 管理者名をチェックする
if ($name == COMMON_INVALID_NAME) {
    echo '管理者名が入力されていません。<br>';
    $isInvalid = true;
} else if (strlen($name) > COMMON_NAME_MAX) {
    printf('管理者名は最大%d文字です。<br>', COMMON_NAME_MAX);
    $isInvalid = true;
} else if (isValidName($name) === false) {
    echo '管理者名に使える文字は、半角の英小文字・英大文字・数字・アンダースコア(_)です。<br>';
    $isInvalid = true;
} else {
    // 正常な場合は何もしない
}

// パスワードをチェックする
if ($password1 == COMMON_INVALID_PASSWORD) {
    echo 'パスワードが入力されていません。<br>';
    $isInvalid = true;
} else if (strlen($password1) > COMMON_PASSWORD_MAX) {
    printf('パスワードは最大%d文字です。<br>', COMMON_PASSWORD_MAX);
    $isInvalid = true;
} else if (isValidPassword($password1) === false) {
    echo 'パスワードに使える文字は、半角の英小文字・英大文字・数字・記号です。<br>';
    echo '使える記号は、!"#$%&\'()*+,-./:;<=>?@[]^_`{|}~です。<br>';
    $isInvalid = true;
} else {
    // 正常な場合は何もしない
}

// 再度入力したパスワードとチェックする
if ($password1 !== $password2) {
    echo 'パスワードが一致しません。<br>';
    $isInvalid = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者修正確認</title>
</head>
<body>
    <?php
    if ($isInvalid) {
        // エラーの場合の表示
        $html = <<< EOM
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
        管理者情報を修正してもよろしいですか？<br>
        <br>
        管理者名：$name<br>
        <form method="POST" action="admin_edit_exec.php">
            <input type="hidden" name="csrf_token" value="$csrfToken">
            <input type="hidden" name="admin_id" value="$adminId">
            <input type="hidden" name="name" value="$name">
            <input type="hidden" name="password" value="$hashPassword">
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