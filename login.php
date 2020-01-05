<?php
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

require('connect.php');

define('LANGUAGE_DIR', $_SERVER['DOCUMENT_ROOT']."/language/", false);

if(isset($_COOKIE['lang'])) {
    $language = $_COOKIE['lang'];
} else {
    setcookie("lang", 'ukr');
    $language = $_COOKIE['lang'];
}

include_once(LANGUAGE_DIR . $language . '.php');

if ($_POST){
    if ($_POST['rus']){
        setcookie("lang", 'rus');
        $language = $_COOKIE['lang'];
    } elseif ($_POST['ukr']) {
        setcookie("lang", 'ukr');
        $language = $_COOKIE['lang'];
    }
    include_once(LANGUAGE_DIR . $language . '.php');
    header("Location: login.php"); exit();
}

if(isset($_POST['submit'])) {
    $login = mysqli_real_escape_string($link,$_POST['login']);
    $query = mysqli_query($link,"SELECT id, password FROM users WHERE email='".$login."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    if($data['password'] === md5(md5($_POST['password']))) {
        $hash = md5(generateCode(10));

        setcookie("id", $data['id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true);

        header("Location: profile.php"); exit();
    } else {
        $err = "Ви ввели неправильний логін/пароль";
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=login?></title>
    <link href="main.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="form">
        <div class="btn-group">
            <form method="post">
                <input type="submit" name="rus" class="btn" value="Рус">
                <input type="submit" name="ukr" class="btn" value="Укр">
            </form>
        </div>
        <form method="POST">
            <h2><?=login?></h2>
            <div class="form-group">
                <label>Email</label>
                <input name="login" type="text" required>
            </div>
            <div class="form-group">
                <label><?=password?></label>
                <input name="password" type="password" required>
                <?php if ($err) { echo '<div class="error">' . loginPassError . '</div>'; } ?>
            </div>
            <input class="button" name="submit" type="submit" value="<?=loginBtn?>">
        </form>
    </div>
</div>
</body>
</html>