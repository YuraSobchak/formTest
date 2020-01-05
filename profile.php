<?php
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
    header("Location: profile.php"); exit();
}

$id = $_COOKIE['id'];
$query = mysqli_query($link,"SELECT avatar, email, address, username, phone, birthday FROM users WHERE id='".$id."'");
$data = mysqli_fetch_assoc($query);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=profile?></title>
    <link href="main.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="profile">
            <div class="group">
                <div class="btn-group">
                    <form method="post">
                        <input type="submit" name="rus" class="btn" value="Рус">
                        <input type="submit" name="ukr" class="btn" value="Укр">
                    </form>
                </div>
                <?php if (!empty($data['avatar'])) { ?>
                    <img class="img" src="uploads/<?php echo $data['avatar'] ?>">
                <?php } else { ?>
                    <img class="img" src="uploads/avatar.png">
                <?php } ?>
                <div class="profile-info"><?=username?>: <?php echo $data['username'] ?></div>
                <div class="profile-info">Email: <?php echo $data['email'] ?></div>
                <div class="profile-info"><?=birthday?>: <?php echo $data['birthday'] ?></div>
                <div class="profile-info"><?=phone?>: <?php echo $data['phone'] ?></div>
                <div class="profile-info"><?=address?>: <?php echo $data['address'] ?></div>
            </div>
        </div>
    </div>
</body>
</html>
