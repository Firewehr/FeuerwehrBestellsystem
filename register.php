<?php

$message = array();
include_once('include/db.php');
error_reporting(E_ALL);



if (!empty($_POST)) {
    if (
            empty($_POST['username']) ||
            empty($_POST['password']) ||
            empty($_POST['password_again'])
    ) {
        $message['error'] = 'Es wurden nicht alle Felder ausgefüllt.';
    } else if ($_POST['password'] != $_POST['password_again']) {
        $message['error'] = 'Die eingegebenen Passwörter stimmen nicht überein.';
        echo 'Die eingegebenen Passwörter stimmen nicht überein.';
    } else {
        unset($_POST['password_again']);
        $salt = '';
        for ($i = 0; $i < 22; $i++) {
            $salt .= substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1);
        }
        $_POST['password'] = crypt(
                $_POST['password'], '$2a$10$' . $salt
        );



        $mysqli = @new mysqli($hostname, $username, $password, $dbname);
        if ($mysqli->connect_error) {
            $message['error'] = 'Datenbankverbindung fehlgeschlagen: ' . $mysqli->connect_error;
        }
        $query = sprintf(
                "INSERT INTO users (username, password)
				SELECT * FROM (SELECT '%s', '%s') as new_user
				WHERE NOT EXISTS (
					SELECT username FROM users WHERE username = '%s'
				) LIMIT 1;", $mysqli->real_escape_string($_POST['username']), $mysqli->real_escape_string($_POST['password']), $mysqli->real_escape_string($_POST['username'])
        );
        $mysqli->query($query);
        if ($mysqli->affected_rows == 1) {
            $message['success'] = 'Neuer Benutzer (' . htmlspecialchars($_POST['username']) . ') wurde angelegt, <a href="login.php">weiter zur Anmeldung</a>.';
            echo 'Neuer Benutzer (' . htmlspecialchars($_POST['username']) . ') wurde angelegt!';
            //header('Location: http://' . $_SERVER['HTTP_HOST'] . '/posNeu/login.php');
        } else {
            $message['error'] = 'Der Benutzername ist bereits vergeben.';
            echo 'Der Benutzername ist bereits vergeben.';
        }
        $mysqli->close();
    }
} else {
    $message['notice'] = 'Übermitteln Sie das ausgefüllte Formular um ein neues Benutzerkonto zu erstellen.';
}
?>