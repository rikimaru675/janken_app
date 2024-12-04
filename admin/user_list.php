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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ一覧</title>
</head>
<body>
    <h1>ユーザ一覧</h1>
    <form method="POST" action="user_branch.php">
        <?php
        // DBからユーザ情報を取得
        $userList = dbGetUserList();
        if (is_array($userList) && count($userList) > 0) {
            // ユーザ情報が登録されている場合は画面表示
            for ($i = 0; $i < count($userList); $i++) {
                printf('<label><input type="radio" name="user_id" value="%d">%s</label>',
                    $userList[$i]['user_id'], $userList[$i]['name']);
                echo '<br>';
            }
            echo '<br>';
            echo '<input type="submit" name="disp" value="参照">';
            echo ' ';   // ボタン位置の調整
            echo '<input type="submit" name="add" value="追加">';
            echo ' ';   // ボタン位置の調整
            echo '<input type="submit" name="edit" value="修正">';
            echo ' ';   // ボタン位置の調整
            echo '<input type="submit" name="del" value="削除">';      
        } else {
            echo 'ユーザが登録されていません。<br>';
            echo '<br>';
            echo '<input type="submit" name="add" value="追加">';
        }
        ?>
        <br><br>
        <a href="admin_menu.php">管理者メニューへ</a>
    </form>
</body>
</html>