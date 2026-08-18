<!-- JM, 08/18/26 -->
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['row'])) {
        $command = true;
        require_once('connect.php');
        $row = htmlspecialchars($_POST['row']);

        $select = "SELECT tally1, tally2, tally3, tally4 FROM vote WHERE id = $row;";
        $tallies = $pdo->query($select)->fetch();
        $set = [];
        foreach ($tallies as $key => $tally) {
            if (!str_contains($key, 'tally'))
                continue;
            if (is_null($tally)) {
                $set[":$key"] = NULL;
            } else {
                $set[":$key"] = 0;
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