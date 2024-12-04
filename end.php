<?php
session_start();

require_once __DIR__ . '/lib/session.php';

// セッションを破棄する
destroySession();

// トップページへリダイレクト
header('Location: .');
