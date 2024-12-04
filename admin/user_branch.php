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

if (isset($post['add'])) {
    // ユーザ追加へリダイレクト
    header('Location: user_add.php');
    exit();
}

// 以下はuser_idが必須
if (!isset($post['user_id'])) {
    // IDが設定されていなければエラー
    header('Location: user_ng.php');
    exit();
}

$userId = $post['user_id'];
if (isset($post['disp'])) {
    // ユーザ情報表示へリダイレクト
    header('Location: user_disp.php?user_id=' . $userId);
    exit();
}

if (isset($post['edit'])) {
    // ユーザ編集へリダイレクト
    header('Location: user_edit.php?user_id=' . $userId);
    exit();
}

if (isset($post['del'])) {
    // ユーザ削除へリダイレクト
    header('Location: user_delete.php?user_id=' . $userId);
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
    <a href="user_list.php">戻る</a>
</body>
</html>