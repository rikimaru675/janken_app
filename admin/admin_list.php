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
    <title>管理者一覧</title>
</head>
<body>
    <h1>管理者一覧</h1>
    <form method="POST" action="admin_branch.php">
        <?php
        // DBから管理者情報を取得
        $adminList = dbGetadminList();
        if (is_array($adminList) && count($adminList) > 0) {
            // 管理者が登録されている場合は画面表示
            for ($i = 0; $i < count($adminList); $i++) {
                printf('<label><input type="radio" name="admin_id" value="%d">%s</label>',
                    $adminList[$i]['admin_id'], $adminList[$i]['name']);
                echo '<br>';
            }
            echo '<br>';
            echo '<input type="submit" name="disp" value="参照">';
            echo ' ';   // ボタン位置の調整
            echo '<input type="submit" name="edit" value="修正">';
        } else {
            echo '管理者が登録されていません。<br>';
        }
        ?>
        <br><br>
        <a href="admin_menu.php">管理者メニューへ</a>
    </form>
</body>
</html>