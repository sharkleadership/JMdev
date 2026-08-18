<!-- JM, 08/18/26 -->
<?php
    require_once('config.php');
    $dsn = "sqlite:$db";
    try {
        $pdo = new PDO($dsn);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
?>