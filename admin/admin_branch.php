<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/../lib/common.php';
require_once __DIR__ . '/../lib/session.php';

// 管理者ログイン済みかチェックする
if (!isAdminLogin()) {
    noLoginHTML('admin_login.php');
    exit();
}

// POSTデータを取得する
$post = h($_POST);

// 以下の処理はIDが必須
if (!isset($post['admin_id'])) {
    // IDが設定されていなければエラー
    header('Location: admin_ng.php');
    exit();
}

$adminId = $post['admin_id'];
if (isset($post['disp'])) {
    // 管理者情報表示へリダイレクト
    header('Location: admin_disp.php?admin_id=' . $adminId);
    exit();
}

if (isset($post['edit'])) {
    // 管理者編集へリダイレクト
    header('Location: admin_edit.php?admin_id=' . $adminId);
    exit();
}

// どれにも当てはまらない操作の場合はエラー
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エラー</title>
</head>
<body>
    不正な操作が行われました。<br>
    <br>
    <a href="admin_list.php">戻る</a>
</body>
</html>