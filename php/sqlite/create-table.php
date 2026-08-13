<?php
    $command = true;
    require_once('connect.php');

    $create_table = "CREATE TABLE IF NOT EXISTS vote (
        id INTEGER PRIMARY KEY,
        question TEXT NOT NULL,
        choices TEXT NOT NULL,
        tally1 INTEGER NOT NULL,
        tally2 INTEGER NOT NULL,
        tally3 INTEGER,
        tally4 INTEGER
    );";

    try {
        $pdo->exec($create_table);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
?>