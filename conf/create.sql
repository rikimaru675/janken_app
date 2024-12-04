-- 既存のデータベースを削除
DROP DATABASE IF EXISTS janken;

-- データベースを作成
CREATE DATABASE janken;

-- データベースを指定
USE janken;

-- 管理者テーブル作成
CREATE TABLE admins (
    admin_id    INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name        VARCHAR(32)     NOT NULL,
    password    VARCHAR(256)    NOT NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (admin_id)
);

-- 管理者テーブルにデフォルト値を設定
-- INSERT INTO admins (name, password) VALUES ('admin', 'password');
-- パスワードはPHPのpassword_hash()関数でハッシュ化した値を設定する
INSERT INTO admins (name, password) VALUES ('admin', '$2y$10$iJc8xyINJttwZk0PRUoeHe9LguUZmlyNg5K3b5LbLB7mSQ3Tbuebm');

-- ユーザテーブル作成
CREATE TABLE users (
    user_id     INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name        VARCHAR(32)     NOT NULL,
    password    VARCHAR(256)    NOT NULL,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
);

-- 戦績テーブル作成
CREATE TABLE results (
    result_id   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id     INT UNSIGNED    NOT NULL,
    win_count   INT UNSIGNED    NOT NULL DEFAULT 0,
    loss_count  INT UNSIGNED    NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (result_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

