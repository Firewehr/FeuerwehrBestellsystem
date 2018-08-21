<?php

if ($_SESSION['admin'] != 1) {
    header('Location: index.php');
}

$pw = $_POST['pw'];
$user = $_POST['user'];
$userid = $_POST['userid'];

$salt = '';
for ($i = 0; $i < 22; $i++) {
    $salt .= substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1);
}
$pw = crypt(
        $pw, '$2a$10$' . $salt
);

echo $pw;

echo '<br>';
echo crypt('1234', '$2a$10$w8aXzAADK9NvQN2FRGQ.9.TLwZChoT4psKE4OAApg5XDIIJz2kSGS');
echo '<br>';

echo crypt('1234', $pw);


require_once('auth.php');

if (intval($userid)>0 && $pw !='') {

    require_once 'include/db.php';
    $sql = "UPDATE `users` SET `password`='" . $pw . "' WHERE id=" . intval($userid);

    //echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}