<?php
    require_once('./login.php');
    try {
        $pdo = new PDO($attr, $user, $pass);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
?>