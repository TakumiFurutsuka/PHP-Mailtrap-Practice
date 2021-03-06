<?php
require_once './Encode.php';
session_start();

if (isset($_POST['age'])) { $_SESSION['age'] = $_POST['age']; }
if (isset($_POST['os']) || isset($_SESSION['os'])) {
    $_SESSION['os'] = $_POST['os']; }
if (isset($_POST['memo'])) { $_SESSION['memo'] = $_POST['memo']; }
if (isset($_POST['quest_num'])) { $_SESSION['quest_num'] = $_POST['quest_num']; }

$errors = array();
foreach($_SESSION as $key => $value) {
    if (is_array($value)) { $value = implode('', $value); }
    if (!mb_check_encoding($value)) {
        $errors[] = '文字コードに誤りがあります。';
        break;
    }
}
if (trim($_SESSION['name']) === '') {
    $errors[] = '名前はかならず入力してください。';
}
if (mb_strlen($_SESSION['name']) > 50) {
    $errors[] = '名前は50文字以内で入力してください。';
}
if (!preg_match('/^[0-9]{3}-[0-9]{4}$/', $_SESSION['zip'])) {
    $errors[] = '不正な郵便番号です。';
}
if (!ctype_digit($_SESSION['age'])) {
    $errors[] = '年齢は整数値で入力してください。';
}
if ($_SESSION['age'] < 10 || $_SESSION['age'] > 50) {
    $errors[] = '年齢は10～50の間で入力してください。';
}
$opts = array('win', 'mac', 'linux');
if (isset($_SESSION['os'])) {
    foreach ($_SESSION['os'] as $os) {
        if (!in_array($os, $opts)) {
            $errors[] = 'OSは決められた選択肢の中から選択してください。';
            break;
        }
    }
}
if (count($errors) > 0) {
    die(implode('<br />', $errors).
        '<br />[<a href="wizard1.php">戻る</a>]');
}

const SUBJECT = 'サイト改善アンケート';

const TO = 'xxxx@xxxx.xxxx';

$headers = <<< "HEAD"
From: {$_SESSION['email']}
Return-Path: {$_SESSION['email']}
Content-Type: text/plain;charset=ISO-2022-JP
HEAD;
$body = "■■".SUBJECT."■■\n";
foreach ($_SESSION as $key => $value) {
    if (is_array($value)) { $value = implode(',', $value); }
    $body .= "［{$key}］ {$value}\n";
}

mb_send_mail(TO, SUBJECT, $body, $headers);

session_destroy();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>PHP</title>
    </head>
    <body>
        <h3>ご回答ありがとうございました。</h3>
        <p>以下の内容で送信致しました。</p>
        <dl>
            <dt>名前：</dt>
            <dd><?php print(es($_SESSION['name'])); ?></dd>
            <dt>メールアドレス：</dt>
            <dd><?php print(es($_SESSION['email'])); ?></dd>
            <dt>郵便番号：</dt>
            <dd><?php print(es($_SESSION['zip'])); ?></dd>
            <dt>性別：</dt>
            <dd><?php print(es($_SESSION['sex'])); ?></dd>
            <dt>年齢：</dt>
            <dd><?php print(es($_SESSION['age'])); ?>代</dd>
            <dt>ご使用のOS：</dt>
            <dd><?php if (isset($_SESSION['os'])) {
    print(es(implode(',', $_SESSION['os']))); } ?></dd>
            <dt>サイトへのご意見：</dt>
            <dd><?php print(nl2br(es($_SESSION['memo']))); ?></dd>
            <dt>アンケート番号：</dt>
            <dd><?php print(es($_SESSION['quest_num'])); ?></dd>
        </dl>
        <?php session_unset(); ?>
    </body>
</html>
