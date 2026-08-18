<!-- JM, 08/18/26 -->
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['row'])) {
        $command = true;
        require_once('connect.php');
        $row = htmlspecialchars($_POST['row']);
        $set = [];
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST["tally$i"])) {
                $set[":tally$i"] = (int) htmlspecialchars($_POST["tally$i"]);
            } else {
                $set[":tally$i"] = NULL;
            }
        }

        $update = "UPDATE vote 
                   SET tally1 = :tally1, tally2 = :tally2, 
                       tally3 = :tally3, tally4 = :tally4 
                   WHERE id = $row;";

        try {
            $stmt = $pdo->prepare($update);
            $stmt->execute($set);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
    }
?>