<?php
include_once 'include/db.php';

if (isset($_SESSION['login'])) {
    header('Location: index.php');
} else {
    if (!empty($_POST)) {
        if (
                empty($_POST['f']['username']) ||
                empty($_POST['f']['password'])
        ) {
            $message['error'] = 'Es wurden nicht alle Felder ausgefüllt.';
        } else {
            $mysqli = @new mysqli($hostname, $username, $password, $dbname);

            if ($mysqli->connect_error) {
                $message['error'] = 'Datenbankverbindung fehlgeschlagen: ' . $mysqli->connect_error;
            }
            $query = sprintf(
                    "SELECT username, password, admin FROM users WHERE username = '%s'", $mysqli->real_escape_string($_POST['f']['username'])
            );
            $result = $mysqli->query($query);
            if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if (crypt($_POST['f']['password'], $row['password']) == $row['password']) {
                    session_start();

                    $_SESSION = array();
                    $_SESSION['login'] = true;
                    $_SESSION['user'] = array(
                        'username' => $row['username']
                    );
                    $_SESSION['admin'] = $row['admin'];

                    $message['success'] = 'Anmeldung erfolgreich, <a href="index.php">weiter zum Inhalt.';


                    header('Location: index.php');
                } else {
                    $message['error'] = 'Das Kennwort ist nicht korrekt.';
                }
            } else {
                $message['error'] = 'Der Benutzer wurde nicht gefunden.';
            }
            $mysqli->close();
        }
    } else {
        
    }
}
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Page Title</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="include/jquery/jquery.mobile-1.4.5.css" />
        <!--<script src="include/jquery/jquery-2.1.4.min.js"></script>
        <script src="include/jquery/jquery.mobile-1.4.5.js"></script>-->
    </head>

    <body>
        <form action="login.php" method="post">
            <table width="300px" border-bottom: 1px solid #ddd;>

                <?php if (isset($message['error'])): ?>
                    <fieldset class="error"><legend>Fehler</legend><?php echo $message['error'] ?></fieldset>
                    <?php
                endif;
                if (isset($message['success'])):
                    ?>
                    <fieldset class="success"><legend>Erfolg</legend><?php echo $message['success'] ?></fieldset>
                    <?php
                endif;
                if (isset($message['notice'])):
                    ?>
                    <fieldset class="notice"><legend>Hinweis</legend><?php echo $message['notice'] ?></fieldset>
                <?php endif; ?>

                <tr>
                    <td>Benutzername</td>
                    <td><input type="text" name="f[username]" id="username"<?php echo isset($_POST['f']['username']) ? ' value="' . htmlspecialchars($_POST['f']['username']) . '"' : '' ?> /></td>
                </tr>
                <tr>
                    <td>Kennwort</td>
                    <td><input type="password" name="f[password]" id="password" /></td>
                </tr>


                <tr>
                    <td><input type="submit" class="ui-btn ui-btn-b" name="submit" value="Anmelden" /></td>
                    <td><input type="reset" class="ui-btn ui-btn-a" value="Reset"></td>
                </tr>


            </table>


        </form>
    </body>
</html>
