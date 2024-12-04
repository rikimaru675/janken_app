<?php
/**********************************************************
 *  定数
 **********************************************************/
/** 共通文字エンコード */
const COMMON_CHAR_ENCODING      = 'UTF-8';
/** 共通文字変換パターン */
const COMMON_ENT_PATTERN        = ENT_QUOTES;

/** 無効なID値 */
const COMMON_INVALID_ID         = -1;
/** 無効な名前 */
const COMMON_INVALID_NAME       = '';
/** 無効なパスワード */
const COMMON_INVALID_PASSWORD   = '';
/** 無効なカウント値 */
const COMMON_INVALID_COUNT      = -1;
/** カウントのデフォルト値 */
const COMMON_DEFAULT_COUNT      = 0;
/** 名前の最小文字数 */
const COMMON_NAME_MIN           = 1;
/** 名前の最大文字数 */
const COMMON_NAME_MAX           = 32;
/** パスワードの最小文字数 */
const COMMON_PASSWORD_MIN       = 1;
/** パスワードの最大文字数 */
const COMMON_PASSWORD_MAX       = 32;

/**********************************************************
 *  関数
 **********************************************************/
/**
 * 文字列のサニタイズ関数
 * 
 * htmlspecialchars関数を使用して文字列をサニタイズする
 * 
 * @param mixed $before         サニタイズ前の文字列
 * @return mixed                サニタイズ後の文字列
 */
function h(mixed $before): mixed
{
    if (is_array($before)) {
        $after = [];
        foreach ($before as $key => $val) {
            $after[$key] = htmlspecialchars(
                            $val,
                            COMMON_ENT_PATTERN,
                            COMMON_CHAR_ENCODING
                        );
        }
        return $after;
    } else {
        $after = htmlspecialchars(
                    $before,
                    COMMON_ENT_PATTERN,
                    COMMON_CHAR_ENCODING
                );
        return $after;
    }
}

/**
 * 未ログイン時に表示するHTMLを取得する
 * 
 * @param string $loginPath     ログイン画面のパス
 * @return void
 */
function noLoginHTML(string $loginPath): void
{
    $html = <<< EOM
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>エラー</title>
    </head>
    <body>
        ログインされていません。<br>
        <br>
        <a href="{$loginPath}">ログインへ</a>
    </body>
    </html>
    EOM;
    echo $html;
}

/**
 * 「名前」で使用する文字列について、有効な文字・文字数かチェックする
 * 
 * @param string $name          名前
 * @return bool                 true:有効な名前
 *                              false:無効な名前
 */
function isValidName(string $name): bool
{
    $result = false;
    $pattern = sprintf('/^[a-zA-Z0-9_]{%d,%d}$/',
                        COMMON_NAME_MIN, COMMON_NAME_MAX);
    if (preg_match($pattern, $name) === 1) {
        $result = true;
    }
    return $result;
}

/**
 * 「パスワード」で使用する文字列について、有効な文字・文字数かチェックする
 * 
 * @param string $password      パスワード
 * @return bool                 true:有効なパスワード
 *                              false:無効なパスワード
 */
function isValidPassword(string $password): bool
{
    $result = false;
    $pattern = sprintf('/^[a-zA-Z0-9!"#$%%&\'()*+,\-\.\/:;<=>?@[\]^_`{|}~]{%d,%d}$/',
                       COMMON_NAME_MIN, COMMON_NAME_MAX);
    if (preg_match($pattern, $password) === 1) {
        $result = true;
    }
    return $result;
}

/**
 * ハッシュ化されたパスワードか否かをチェックする
 * 
 * @param string $password      パスワード
 * @return bool                 true:ハッシュ化されている
 *                              false:ハッシュ化されていない
 */
function isHashedPassword(string $password): bool
{
    $result = false;
    if (strlen($password) == 60 &&               // 現状は60文字固定のため
        substr($password, 0, 4) == '$2y$') {     // 識別子「$2y$」
        $result = true;
    }
    return $result;
}

/**
 * CSRF対策用のワンタイムトークンを取得する
 * 
 * @return string               ワンタイムトークン
 */
function getCsrfToken(): string
{
    // ワンタイムトークンの生成
    return bin2hex(random_bytes(32));
}
