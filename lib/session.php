<?php
/**
 * 【注意】
 * 以下のセッション関連の関数は session_start() が呼び出された後に使用すること
 * 
 * 【セッション変数】
 * $_SESSION['admin_login']     管理者ログイン状態かを保持する
 * $_SESSION['admin_id']        管理者IDを保持する
 * $_SESSION['admin_name']      管理者名を保持する
 * $_SESSION['user_login']      ユーザログイン状態かを保持する
 * $_SESSION['user_id']         ユーザIDを保持する
 * $_SESSION['user_name']       ユーザ名を保持する
 * $_SESSION['win_count']       じゃんけんの勝ち数を保持する
 * $_SESSION['loss_count']      じゃんけんの負け数を保持する
 * $_SESSION['csrf_token']      CSRF対策のトークンを保持する
*/

/**
 * セッションを開始する
 * 
 * @param bool $isRegenerateID  セッションIDを再生成するか否か
 * @return void
 */
function startSession(bool $isRegenerateId): void
{
    session_start();
    if ($isRegenerateId) {
        session_regenerate_id(true);
    }
}

/**
 * セッションを破棄する
 * 
 * セッション変数を空にし、クッキーを破棄する
 * 必ず session_star() を記述した後に使用すること
 * 
 * @return void
 */
function destroySession(): void
{
    // セッション変数を破棄
    session_unset();
    // クッキーを破棄
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    // セッションを破棄
    session_destroy();
}

/**
 * セッション変数に設定されている管理者IDを取得する
 * 
 * セッション変数に設定されていない場合や数値でない場合はCOMMON_INVALID_IDを返す
 * 
 * @return int                  管理者ID
 *                              COMMON_INVALID_ID
 */
function getSessionAdminId(): int
{
    $id = COMMON_INVALID_ID;
    if (isset($_SESSION['admin_id']) && is_numeric($_SESSION['admin_id'])) {
        $id = intval($_SESSION['admin_id']);
    }
    return $id;
}

/**
 * セッション変数に設定されている管理者名を取得する
 * 
 * セッション変数に設定されていない場合や空の場合はCOMMON_INVALID_NAMEを返す
 * 
 * @return string               管理者名
 *                              COMMON_INVALID_NAME
 */
function getSessionAdminName(): string
{
    $name = COMMON_INVALID_NAME;
    if (isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name'])) {
        $name = $_SESSION['admin_name'];
    }
    return $name;
}

/**
 * セッション変数に管理者ログイン状態を設定する
 * 
 * @return void
 */
function setSessionAdminLogin(): void
{
    $_SESSION['admin_login'] = true;
}

/**
 * セッション変数に管理者IDを設定する
 * 
 * @param int $id               管理者ID
 * @return void
 */
function setSessionAdminId(int $id): void
{
    $_SESSION['admin_id'] = $id;
}

/**
 * セッション変数に管理者名を設定する
 * 
 * @param string $name          管理者名
 * @return void
 */
function setSessionAdminName(string $name): void
{
    $_SESSION['admin_name'] = $name;
}

/**
 * セッション変数に管理者系のデータを設定する
 * 
 * @param int $id           管理者ID
 * @param string $name      管理者名
 * @return void
 */
function setSessionAdminData(int $id, string $name): void
{
    setSessionAdminLogin();
    setSessionAdminId($id);
    setSessionAdminName($name);
}

/**
 * 管理者ログイン中か否かを判定する
 * 
 * @return bool             true:管理者ログイン中
 *                          false:管理者ログイン中ではない
 */
function isAdminLogin(): bool
{
    return isset($_SESSION['admin_login']);
}

/**
 * セッション変数に設定されているユーザIDを取得する
 * 
 * セッション変数に設定されていない場合や数値でない場合はCOMMON_INVALID_IDを返す
 * 
 * @return int                  ユーザID
 *                              COMMON_INVALID_ID
 */
function getSessionUserId(): int
{
    $id = COMMON_INVALID_ID;
    if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
        $id = intval($_SESSION['user_id']);
    }
    return $id;
}

/**
 * セッション変数に設定されているユーザ名を取得する
 * 
 * セッション変数に設定されていない場合や空の場合はCOMMON_INVALID_NAMEを返す
 * 
 * @return string               ユーザ名
 *                              COMMON_INVALID_NAME
 */
function getSessionUserName(): string
{
    $name = COMMON_INVALID_NAME;
    if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
        $name = $_SESSION['user_name'];
    }
    return $name;
}

/**
 * セッション変数に設定されている勝ち数を取得する
 * 
 * セッション変数に設定されていない場合や数値でない場合はCOMMON_INVALID_COUNTを返す
 * 
 * @return int                  勝ち数
 *                              COMMON_INVALID_COUNT
 */
function getSessionWinCount(): int
{
    $count = COMMON_INVALID_COUNT;
    if (isset($_SESSION['win_count']) && is_numeric($_SESSION['win_count'])) {
        $count = intval($_SESSION['win_count']);
    }
    return $count;
}

/**
 * セッション変数に設定されている負け数を取得する
 * 
 * セッション変数に設定されていない場合や数値でない場合はCOMMON_INVALID_COUNTを返す
 * 
 * @return int                  負け数
 *                              COMMON_INVALID_COUNT
 */
function getSessionLossCount(): int
{
    $count = COMMON_INVALID_COUNT;
    if (isset($_SESSION['loss_count']) && is_numeric($_SESSION['loss_count'])) {
        $count = intval($_SESSION['loss_count']);
    }
    return $count;
}

/**
 * セッション変数にユーザログイン状態を設定する
 * 
 * @return void
 */
function setSessionUserLogin(): void
{
    $_SESSION['user_login'] = true;
}

/**
 * セッション変数にユーザIDを設定する
 * 
 * @param int $id               ユーザID
 * @return void
 */
function setSessionUserId(int $id): void
{
    $_SESSION['user_id'] = $id;
}

/**
 * セッション変数にユーザ名を設定する
 * 
 * @param string $name          ユーザ名
 * @return void
 */
function setSessionUserName(string $name): void
{
    $_SESSION['user_name'] = $name;
}

/**
 * セッション変数に勝ち数を設定する
 * 
 * @param int $count            勝ち数
 * @return void
 */
function setSessionWinCount(int $count): void
{
    $_SESSION['win_count'] = $count;
}

/**
 * セッション変数に負け数を設定する
 * 
 * @param int $count            負け数
 * @return void
 */
function setSessionLossCount(int $count): void
{
    $_SESSION['loss_count'] = $count;
}

/**
 * セッション変数にユーザ系のデータを設定する
 * 
 * @param int $id           ユーザID
 * @param string $name      ユーザ名
 * @param int $win          勝ち数
 * @param int $loss         負け数
 * @return void
 */
function setSessionUserData(int $id, string $name,
                            int $win, int $loss): void
{
    setSessionUserLogin();
    setSessionUserId($id);
    setSessionUserName($name);
    setSessionWinCount($win);
    setSessionLossCount($loss);
}

/**
 * ユーザログイン中か否かを判定する
 * 
 * @return bool             true:ユーザログイン中
 *                          false:ユーザログイン中ではない
 */
function isUserLogin(): bool
{
    return isset($_SESSION['user_login']);
}

/**
 * セッション変数にユーザIDが設定されているか否かを返す
 * 
 * @return bool             true:設定されている
 *                          false:設定されていない
 */
function isSetSessionUserId(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * セッション変数にユーザ名が設定されているか否かを返す
 * 
 * @return bool             true:設定されている
 *                          false:設定されていない
 */
function isSetSessionUserName(): bool
{
    return isset($_SESSION['user_name']);
}

/**
 * セッション変数に勝ち数が設定されているか否かを返す
 * 
 * @return bool             true:設定されている
 *                          false:設定されていない
 */
function isSetSessionWinCount(): bool
{
    return isset($_SESSION['win_count']);
}

/**
 * セッション変数に負け数が設定されているか否かを返す
 * 
 * @return bool             true:設定されている
 *                          false:設定されていない
 */
function isSetSessionLossCount(): bool
{
    return isset($_SESSION['loss_count']);
}

/**
 * セッション変数にCSRFトークンを設定する
 * 
 * @param string $token     CSRFトークン
 * @return void
 */
function setSessionCsrfToken(string $token): void
{
    $_SESSION['csrf_token'] = $token;
}

/**
 * セッション変数に設定されているCSRFトークンと一致するか否かを比較する
 * 
 * @param string $token     CSRFトークン
 * @return bool             true:一致する
 *                          false:一致しない
 */
function compareSessionCsrfToken(string $token): bool
{
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

/**
 * セッション変数に設定されているCSRFトークンを削除する
 * 
 * @return void
 */
function deleteSessionCsrfToken(): void
{
    if (isset($_SESSION['csrf_token'])) {
        unset($_SESSION['csrf_token']);
    }
}

/**
 * CSRFトークンエラー時に表示するHTMLを表示する
 * 
 * CSRFトークンエラー時はセッションをすべて破棄する
 * 
 * @param string $path          戻り先のパス
 * @return void
 */
function csrfTokenErrorHTML(string $path): void
{
    // セッションを破棄する
    destroySession();

    $html = <<< EOM
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>エラー</title>
    </head>
    <body>
        不正な処理が行われました。<br>
        <br>
        <a href="{$path}">戻る</a>
    </body>
    </html>
    EOM;
    echo $html;
}