<?php
require_once __DIR__ . '/../conf/conf_db.php';

// デバッグ設定(true:ON / false:OFF)
const DB_DEBUG = true;

// DB設定関連定義
const DB_KIND = 'mysql';
const DB_PORT = 3306;
const DB_CHARSET = 'utf8';

// テーブル関連定義
const DB_TABLE_ADMIN = 'admins';
const DB_TABLE_USER = 'users';
const DB_TABLE_RESULT = 'results';

// カラム関連定義
const DB_ADMIN_ID = 'admin_id';
const DB_ADMIN_NAME = 'name';
const DB_ADMIN_PASSWORD = 'password';
const DB_ADMIN_CREATED = 'created_at';
const DB_ADMIN_UPDATED = 'updated_at';

const DB_USER_ID = 'user_id';
const DB_USER_NAME = 'name';
const DB_USER_PASSWORD = 'password';
const DB_USER_CREATED = 'created_at';
const DB_USER_UPDATED = 'updated_at';

const DB_RESULT_ID = 'result_id';
const DB_RESULT_USER_ID = 'user_id';
const DB_RESULT_WIN = 'win_count';
const DB_RESULT_LOSS = 'loss_count';
const DB_RESULT_CREATED = 'created_at';
const DB_RESULT_UPDATED = 'updated_at';

/**
 * データベースと接続する
 * 
 * @return PDO                  PDOオブジェクト
 */
function getDB(): PDO
{
    $dns = sprintf('%s:dbname=%s; host=%s; port=%d; charset=%s',
                    DB_KIND, CONF_DB_NAME, CONF_DB_HOST, DB_PORT, DB_CHARSET);
    $usr = CONF_DB_USER;
    $pwd = CONF_DB_PASSWORD;

    $db = new PDO($dns, $usr, $pwd);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
}

/**
 * データベース例外時のエラーメッセージを取得する
 * 
 * @return string               エラーメッセージ
 */
function dbErrorMessage(PDOException $e): string
{
    return 'データベースエラー : ' . $e->getMessage();
}

/**
 * 例外時のエラーメッセージを取得する
 * 
 * @return string               エラーメッセージ
 */
function dbOtherErrorMessage(Exception $e): string
{
    return 'その他エラー：'. $e->getMessage();
}

/**
 * 指定した管理者IDの管理者データを管理者テーブルから取得する
 * 
 * @param int $admin_id         管理者ID
 * @return mixed                fetch()の戻り値
 *                              例外発生時はfalse
 */
function dbGetAdminDataById(int $admin_id): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM admins
                WHERE admin_id = :admin_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定した管理者名の管理者データを管理者テーブルから取得する
 * 
 * @param int $name             管理者名
 * @return mixed                fetch()の戻り値
 *                              例外発生時はfalse
 */
function dbGetAdminDataByName(string $name): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM admins
                WHERE name = :name';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * すべての管理者データを管理者テーブルから取得する
 * 
 * @return mixed                fetchAll()の戻り値
 *                              例外発生時はfalse
 */
function dbGetAdminList(): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM admins';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 管理者テーブルに管理者データを追加する
 * 
 * @param string $name          管理者名
 * @param string $password      パスワード
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbAddAdminData(string $name, string $password): bool
{
    try {
        $dbh = getDB();
        $sql = 'INSERT INTO
                admins (name, password)
                VALUES (:name, :password)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 管理者テーブルの管理者データを編集する
 * 
 * @param int $admin_id         管理者ID
 * @param string $name          管理者名
 * @param string $password      パスワード
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbEditAdminData(int $admin_id, string $name, string $password): bool
{
    try {
        $dbh = getDB();
        $sql = 'UPDATE admins
                SET name = :name, password = :password
                WHERE admin_id = :admin_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定した管理者IDの管理者データを管理者テーブルから削除する
 * 
 * @param int $admin_id         管理者ID
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbDelAdminData(int $admin_id): bool
{
    try {
        $dbh = getDB();
        $sql = 'DELETE
                FROM admins
                WHERE admin_id = :admin_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザIDのユーザデータをユーザテーブルから取得する
 * 
 * @param int $user_id          ユーザID
 * @return mixed                fetch()の戻り値
 *                              例外発生時はfalse
 */
function dbGetUserDataById(int $user_id): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM users
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザ名のユーザデータをユーザテーブルから取得する
 * 
 * @param int $name             ユーザ名
 * @return mixed                fetch()の戻り値
 *                              例外発生時はfalse
 */
function dbGetUserDataByName(string $name): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM users
                WHERE name = :name';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * すべてのユーザデータをユーザテーブルから取得する
 * 
 * @return mixed                fetchAll()の戻り値
 *                              例外発生時はfalse
 */
function dbGetUserList(): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM users';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * ユーザテーブルにユーザデータを追加する
 * 
 * @param string $name          ユーザ名
 * @param string $password      パスワード
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbAddUserData(string $name, string $password): bool
{
    try {
        $dbh = getDB();
        $sql = 'INSERT INTO
                users (name, password)
                VALUES (:name, :password)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * ユーザテーブルのユーザデータを編集する
 * 
 * @param int $user_id          ユーザID
 * @param string $name          ユーザ名
 * @param string $password      パスワード
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbEditUserData(int $user_id, string $name, string $password): bool
{
    try {
        $dbh = getDB();
        $sql = 'UPDATE users
                SET name = :name, password = :password
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザIDのユーザデータをユーザテーブルから削除する
 * 
 * @param int $user_id          ユーザID
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbDelUserData(int $user_id): bool
{
    try {
        $dbh = getDB();
        $sql = 'DELETE
                FROM users
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザIDの戦績データを戦績テーブルから取得する
 * 
 * @param int $user_id          ユーザID
 * @return mixed                fetch()の戻り値
 *                              例外発生時はfalse
 */
function dbGetResultDataById(int $user_id): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM results
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 戦績テーブルに戦績データを追加する
 * 
 * @param int $user_id          ユーザID
 * @param int $win_count        勝ち数
 * @param int $loss_count       負け数
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbAddResultData(int $user_id, int $win_count = 0, int $loss_count = 0): bool
{
    try {
        $dbh = getDB();
        $sql = 'INSERT INTO
                results (user_id, win_count, loss_count)
                VALUES (:user_id, :win_count, :loss_count)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':win_count', $win_count, PDO::PARAM_INT);
        $stmt->bindValue(':loss_count', $loss_count, PDO::PARAM_INT);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 戦績テーブルの戦績データを編集する
 * 
 * @param int $user_id          ユーザID
 * @param int $win_count        勝ち数
 * @param int $loss_count       負け数
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbEditResultData(int $user_id, int $win_count, int $loss_count): bool
{
    try {
        $dbh = getDB();
        $sql = 'UPDATE results
                SET win_count = :win_count,
                    loss_count = :loss_count
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':win_count', $win_count, PDO::PARAM_INT);
        $stmt->bindValue(':loss_count', $loss_count, PDO::PARAM_INT);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザIDの戦績データを戦績テーブルから削除する
 * 
 * @param int $user_id          ユーザID
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbDelResultData(int $user_id): bool
{
    try {
        $dbh = getDB();
        $sql = 'DELETE
                FROM results
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザIDのユーザデータと戦績データを
 * ユーザテーブルと戦績テーブルから取得する
 * 
 * @param int $user_id          ユーザID
 * @return mixed                fetch()の戻り値
 *                              例外発生時はfalse
 */
function dbGetUserCountDataById(int $user_id): mixed
{
    try {
        $dbh = getDB();
        $sql = 'SELECT *
                FROM users AS u
                INNER JOIN results AS r
                ON u.user_id = r.user_id
                WHERE u.user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * ユーザテーブルと戦績テーブルにユーザデータと戦績データを追加する
 * 
 * @param string $name          ユーザ名
 * @param string $password      パスワード
 * @param int $win_count        勝ち数
 * @param int $loss_count       負け数
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbAddUserResultData(string $name, string $password,
                            int $win_count = 0,
                            int $loss_count = 0): bool
{
    try {
        $dbh = getDB();
        $dbh->beginTransaction();

        $sql = 'INSERT INTO
        users (name, password)
        VALUES (:name, :password)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        // 追加したユーザのユーザIDを取得する
        $user_id = (int)$dbh->lastInsertId();

        $sql = 'INSERT INTO
                results (user_id, win_count, loss_count)
                VALUES (:user_id, :win_count, :loss_count)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':win_count', $win_count, PDO::PARAM_INT);
        $stmt->bindValue(':loss_count', $loss_count, PDO::PARAM_INT);
        $stmt->execute();

        $dbh->commit();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $dbh->rollBack();
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $dbh->rollBack();
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * ユーザテーブルのユーザデータと戦績テーブルの戦績データを編集する
 * 
 * @param int $user_id          ユーザID
 * @param string $name          ユーザ名
 * @param string $password      パスワード
 * @param int $win_count        勝ち数
 * @param int $loss_count       負け数
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbEditUserResultData(int $user_id, string $name, string $password,
                             int $win_count, int $loss_count): bool 
{
    try {
        $dbh = getDB();
        $sql = 'UPDATE
                users AS u
                INNER JOIN results AS r
                ON u.user_id = r.user_id
                SET u.name = :name,
                    u.password = :password,
                    r.win_count = :win_count,
                    r.loss_count = :loss_count
                WHERE u.user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':win_count', $win_count, PDO::PARAM_INT);
        $stmt->bindValue(':loss_count', $loss_count, PDO::PARAM_INT);
        $stmt->execute();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}

/**
 * 指定したユーザIDのユーザデータと戦績データを
 * ユーザテーブルと戦績テーブルから削除する
 * 
 * @param int $user_id          ユーザID
 * @return bool                 true:成功
 *                              false:失敗
 */
function dbDelUserResultData(int $user_id): bool
{
    try {
        $dbh = getDB();
        $dbh->beginTransaction();

        // 外部キーのレコードを先に削除
        $sql = 'DELETE
                FROM results
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // 主キーのレコードを削除
        $sql = 'DELETE
                FROM users
                WHERE user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $dbh->commit();
        $result = true;
    } catch (PDOException $e) {
        if (DB_DEBUG)   echo dbErrorMessage($e);
        $dbh->rollBack();
        $result = false;
    } catch (Exception $e) {
        if (DB_DEBUG)   echo dbOtherErrorMessage($e);
        $dbh->rollBack();
        $result = false;
    } finally {
        $dbh = null;
    }
    return $result;
}
