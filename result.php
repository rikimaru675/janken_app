<?php
session_start();
session_regenerate_id(true);

require_once __DIR__ . '/lib/common.php';
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/session.php';
require_once __DIR__ . '/lib/janken.php';

$userId = getSessionUserId();
$userName = getSessionUserName();
$winCount = getSessionWinCount();
$lossCount = getSessionLossCount();
$playerHand = JANKEN_HAND_INVALID;

// POSTデータを取得する
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
if (isset($post['hand']) && is_numeric($post['hand'])) {
    $playerHand = intval($post['hand']);
}

// コンピュータの手を取得
$comHand = getRandomJankenHand();
if (!isRightJankenHand($playerHand) ||
    !isRightJankenHand($comHand)) {
    // じゃんけんの手が異常
    $result = '不正な手です。<br>';
} else {
    // じゃんけんの手が正常
    // じゃんけんの勝敗を判定
    $judge = judgeJankenHand($playerHand, $comHand);
    if ($judge == JANKEN_JUDGE_WIN) {
        $winCount++;
        if ($winCount < 0)  $winCount = 0;
        setSessionWinCount($winCount);
        $result = <<< EOM
            {$userName}さんの勝ち。<br>
            戦績：{$winCount}勝{$lossCount}敗<br>
        EOM;
    } elseif ($judge == JANKEN_JUDGE_LOSS)  {
        $lossCount++;
        if ($lossCount < 0) $lossCount = 0;
        setSessionLossCount($lossCount);
        $result = <<< EOM
            {$userName}さんの負け。<br>
            戦績：{$winCount}勝{$lossCount}敗<br>
        EOM;
    } else {
        $result = <<< EOM
            あいこです。<br>
            もう一度、じゃんけんの手を選択してください。<br>
        EOM;
    }

    if (isUserLogin() &&
        ($judge == JANKEN_JUDGE_WIN || $judge == JANKEN_JUDGE_LOSS)) {
        // 勝敗についてデータベースを更新
        dbEditResultData($userId, $winCount, $lossCount);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけん結果</title>
</head>
<body>
    <h1>じゃんけんの結果</h1>
    <?= $result ?>
    <br>
    <?php showJankenHand($playerHand, $comHand) ?>
    <br>
    <a href="select.php">じゃんけんの手の選択へ</a><br>
    <br>
    <a href="end.php">じゃんけんをやめる</a><br>
</body>
</html>