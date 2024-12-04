<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';
require_once __DIR__ . '/lib/janken.php';

// 各種セッションデータが定義されていなければ(ゲストの場合)、ここで設定しておく
if (!isSetSessionUserName())    setSessionUserName(JANKEN_GUEST_NAME);
if (!isSetSessionWinCount())    setSessionWinCount(COMMON_DEFAULT_COUNT);
if (!isSetSessionLossCount())   setSessionLossCount(COMMON_DEFAULT_COUNT);

// セッションデータを取得する
$userName = getSessionUserName();
$winCount = getSessionWinCount();
$lossCount = getSessionLossCount();

// csrfトークンの作成
$csrfToken = getCsrfToken();
// トークンをセッションへ保存
setSessionCsrfToken($csrfToken);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけんの手選択</title>
</head>
<body>
    <h1>じゃんけんの手選択</h1>
    <?= $userName ?>さん<br>
    戦績：<?= $winCount ?>勝<?= $lossCount ?>敗<br>
    <br>
    じゃんけんの手を選んでください。<br>
    <br>
    <form method="POST" action="result.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <div>
            <input type="radio" id="rock" name="hand" value="<?= JANKEN_HAND_ROCK ?>" checked>
            <label for="rock">グー</label><br>
        </div>
        <div>
            <input type="radio" id="scissors" name="hand" value="<?= JANKEN_HAND_SCISSORS ?>">
            <label for="scissors">チョキ</label><br>
        </div>
        <div>
            <input type="radio" id="paper" name="hand" value="<?= JANKEN_HAND_PAPER ?>">
            <label for="paper">パー</label><br>
        </div>
        <br>
        <input type="submit" value="OK">
    </form>
    <br>
    <a href="end.php">じゃんけんをやめる</a><br>
    <?php if (isUserLogin()): ?>
    <br>
    <a href="user_menu.php">ユーザ設定</a>
    <?php endif ?>
</body>
</html>