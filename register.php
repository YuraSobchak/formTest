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
    header("Location: register.php"); exit();
}

if(isset($_POST['submit'])) {
    $errors = [];

    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['username'])) {
        $nameError = "Ім'я користувача може складатись лише з букв англійського алфавіту і цифр";
        $errors[] = $nameError;
    }

    if(preg_match("/[^,.0-9]/",$_POST['phone'])) {
        $phoneNumberError = "Номер телефону може складатись лише з цифр";
        $errors[] = $phoneNumberError;
    }

    if(strlen($_POST['username']) < 3 or strlen($_POST['username']) > 30) {
        $nameLengthError = "Ім'я користувача повинне бути не менше 3-х символів і не більше 30";
        $errors[] = $nameLengthError;
    }

    if(strlen($_POST['email']) < 3 or strlen($_POST['email']) > 30) {
        $emailError = "Email повинен бути не менше 3-х символів і не більше 30";
        $errors[] = $emailError;
    }

    if(strlen($_POST['password']) < 6 or strlen($_POST['password']) > 30) {
        $passwordError = "Пароль повинен бути не менше 7 символів і не більше 30";
        $errors[] = $passwordError;
    }

    if(strlen($_POST['phone']) != 10) {
        $phoneError = "Номер телефону складається з 10 цифр";
        $errors[] = $phoneError;
    }

    $date = new DateTime();
    if($_POST['birthday'] > $date->format('yy-m-d')) {
        $birthdayError = "Дата народження не дійсна";
        $errors[] = $birthdayError;
    }

    $query = mysqli_query($link, "SELECT id FROM users WHERE username='".$_POST['username']."'");
    if(mysqli_num_rows($query) > 0) {
        $nameExist = "Користувач з таким іменем вже існує";
        $errors[] = $nameExist;
    }

    $query = mysqli_query($link, "SELECT id FROM users WHERE email='".$_POST['email']."'");
    if(mysqli_num_rows($query) > 0) {
        $emailExist = "Користувач з таким email вже існує";
        $errors[] = $emailExist;
    }

    if(!$_FILES['avatar']['error'] > 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $fileError = "Файл повинен бути зображенням";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $typeError = "Дозволені файли тільки JPG, JPEG, PNG та GIF формату";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
        }
    }

    if(count($errors) == 0) {

        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($link,"INSERT INTO users SET username='".$_POST['username']."', password='".$password."', address='".$_POST['address']."', phone='".$_POST['phone']."', avatar='".$_FILES['avatar']['name']."', email='".$_POST['email']."', birthday='".$_POST['birthday']."'");
        header("Location: login.php"); exit();
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?=register?></title>
    <link href="main.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
        <form enctype="multipart/form-data" method="POST">
            <h2><?=register?></h2>
            <div class="form-group">
                <label><?=username?></label>
                <input name="username" type="text" required>
                <?php
                if ($nameError) {
                    echo '<div class="error">' . nameError . '</div>';
                }
                if ($nameLengthError) {
                    echo '<div class="error">' . nameLengthError . '</div>';
                }
                if ($nameExist) {
                    echo '<div class="error">' . nameExist . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <label><?=birthday?></label>
                <input name="birthday" type="date" required>
                <?php
                if ($birthdayError) {
                    echo '<div class="error">' . birthdayError . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email" required>
                <?php
                if ($emailError) {
                    echo '<div class="error">' . emailError . '</div>';
                }
                if ($emailExist) {
                    echo '<div class="error">' . emailExist . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <label><?=phone?></label>
                <input name="phone" id="phone" type="text" placeholder="0950000000" required>
                <?php
                if ($phoneError) {
                    echo '<div class="error">' . phoneError . '</div>';
                }
                if ($phoneNumberError) {
                    echo '<div class="error">' . phoneNumberError . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <label><?=address?></label>
                <input name="address" type="text">
            </div>
            <div class="form-group">
                <label><?=password?></label>
                <input name="password" type="password" required>
                <?php
                if ($passwordError) {
                    echo '<div class="error">' . passwordError . '</div>';
                }
                ?>
            </div>
            <div class="form-group">
                <label><?=avatar?></label>
                <input type="file" name="avatar" multiple accept=".png,.jpg,.gif">
                <?php
                if ($fileError) {
                    echo '<div class="error">' . fileError . '</div>';
                }
                if ($typeError) {
                    echo '<div class="error">' . typeError . '</div>';
                }
                ?>
            </div>
            <input class="button" name="submit" type="submit" value="<?=registerBtn?>">
        </form>
    </div>
</div>
<script type="text/javascript">
    $('#phone').keypress(function (e) {
        if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });
</script>
</body>
</html>
