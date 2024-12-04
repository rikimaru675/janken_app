<?php
/** @var string ゲストの名前 */
const JANKEN_GUEST_NAME     = 'ゲスト';
/** @var string コンピュータの名前 */
const JANKEN_COM_NAME       = 'COM';
/** @var int    じゃんけんの手：不正 */
const JANKEN_HAND_INVALID   = -1;
/** @var int    じゃんけんの手：グー */
const JANKEN_HAND_ROCK      = 0;
/** @var int    じゃんけんの手：チョキ */
const JANKEN_HAND_SCISSORS  = 1;
/** @var int    じゃんけんの手：パー */
const JANKEN_HAND_PAPER     = 2;

/** @var string じゃんけんの判定結果：勝ち */
const JANKEN_JUDGE_WIN      = 'win';
/** @var string じゃんけんの判定結果：負け */
const JANKEN_JUDGE_LOSS     = 'loss';
/** @var string じゃんけんの判定結果：あいこ */
const JANKEN_JUDGE_DRAW     = 'draw';

/** @var array  じゃんけんの手の名前 */
const JANKEN_HAND_NAME = [
    JANKEN_HAND_INVALID     => '不正',
    JANKEN_HAND_ROCK        => 'グー',
    JANKEN_HAND_SCISSORS    => 'チョキ',
    JANKEN_HAND_PAPER       => 'パー',
];

/**
 * じゃんけんの手を取得する
 * 
 * グー・チョキ・パーのいずれかをランダムで以下の定数で返す<br>
 * JANKEN_HAND_ROCK     ・・・グー<br>
 * JANKEN_HAND_SCISSORS ・・・チョキ<br>
 * JANKEN_HAND_PAPER    ・・・パー
 * 
 * @param void      なし
 * @return int      じゃんけんの手
 */
function getRandomJankenHand(): int
{
    $min = JANKEN_HAND_ROCK;
    $max = JANKEN_HAND_PAPER;

    return mt_rand($min, $max);
}

/**
 * じゃんけんの手を表示する
 * 
 * プレイヤーとコンピュータの手を表示する
 * 
 * @param int $playerHand   プレイヤーのじゃんけんの手
 * @param int $comHand      コンピュータのじゃんけんの手
 * @return void             なし
 */
function showJankenHand(int $playerHand, int $comHand): void
{
    if (!isRightJankenHand($playerHand))    $playerHand = JANKEN_HAND_INVALID;
    if (!isRightJankenHand($comHand))       $comHand = JANKEN_HAND_INVALID;

    printf("あなた：%s vs. %s：%s<br>",
           JANKEN_HAND_NAME[$playerHand],
           JANKEN_COM_NAME,
           JANKEN_HAND_NAME[$comHand]);
}

/**
 * じゃんけんの手が正しいか否かを判定する
 * 
 * @param string $hand      じゃんけんの手
 * @return bool             true:正しい/false:正しくない
 */
function isRightJankenHand(string $hand): bool
{
    $result = false;
    $pattern = sprintf('/^[%d-%d]$/',
        JANKEN_HAND_ROCK, JANKEN_HAND_PAPER);

    if (preg_match($pattern, $hand) === 1) {
        $result = true;
    }

    return $result;
}

/**
 * じゃんけんの勝敗を判定する
 * 
 * じゃんけんの結果が勝ち・負け・あいこか判定し、以下定数で返す<br>
 * JANKEN_JUDGE_WIN     ・・・勝ち<br>
 * JANKEN_JUDGE_LOSS    ・・・負け<br>
 * JANKEN_JUDGE_DRAW    ・・・あいこ
 * 
 * @param int $playerHand   プレイヤーのじゃんけんの手
 * @param int $comHand      コンピュータのじゃんけんの手
 * @return string           じゃんけんの勝敗
 */
function judgeJankenHand(int $playerHand, int $comHand): string
{
    if ($playerHand == $comHand) {
        $result = JANKEN_JUDGE_DRAW;
    } elseif (($playerHand == JANKEN_HAND_ROCK     && $comHand == JANKEN_HAND_SCISSORS) ||
              ($playerHand == JANKEN_HAND_SCISSORS && $comHand == JANKEN_HAND_PAPER   ) ||
              ($playerHand == JANKEN_HAND_PAPER    && $comHand == JANKEN_HAND_ROCK    )) {
        $result = JANKEN_JUDGE_WIN;
    } else {
        $result = JANKEN_JUDGE_LOSS;
    }

    return $result;
}
