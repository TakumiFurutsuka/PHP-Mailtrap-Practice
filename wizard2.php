<?php
require_once './Encode.php';
session_start();

if (isset($_POST['name'])) {$_SESSION['name'] = $_POST['name'];}
if (isset($_POST['email'])) {$_SESSION['email'] = $_POST['email'];}
if (isset($_POST['sex'])) {$_SESSION['sex'] = $_POST['sex'];}
if (isset($_POST['zip'])) {$_SESSION['zip'] = $_POST['zip'];}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>PHP</title>
    </head>
    <body>
        <h3>アンケート</h3>
        <form method="POST" action="wizard3.php">
            <div class="container">
                <label for="age">年齢：</label>
                <select id="age" name="age">
                    <?php
for ($i = 10; $i <= 50; $i += 10) {
    print('<option value="' . $i . '"');
    if (isset($_SESSION['age']) && $i === (int) $_SESSION['age']) {print(' selected');}
    print('>' . $i . '代</option>');
}
?>
                </select>
            </div>
            <div class="container">
                ご使用のOS：
                <?php
$oss = array('win' => 'Windows', 'mac' => 'Mac',
    'linux' => 'Linux');
foreach ($oss as $k_os => $v_os) {
    print('<label>');
    print('<input type="checkbox" name="os[]" value="' . $k_os . '"');
    if (isset($_SESSION['os'])) {
        foreach ($_SESSION['os'] as $os) {
            if ($k_os === $os) {print(' checked');}
        }
    }
    print(' />');
    print($v_os . '</label>');
}
?>
            </div>
            <div class="container">
                <label for="memo">サイトへのご意見：</label><br />
                <textarea rows="5" cols="30" id="memo"
                          name="memo"><?php isset($_SESSION['memo']) ? print(es($_SESSION['memo'])) : '';?></textarea>
            </div>
            <input type="hidden" name="quest_num" value="XXX15204" />
            <input type="button" value="前へ" onclick="location.href='wizard1.php'";/>
            <input type="submit" value="送信" />
        </form>
    </body>
</html>
