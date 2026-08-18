<!-- JM, 08/18/26 -->
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['row'])) {
        $command = true;
        require_once('connect.php');
        $row = htmlspecialchars($_GET['row']);

        $select = "SELECT * FROM vote WHERE id = $row;";
        
        try {
            $row = $pdo->query($select)->fetch();
            $json = [
                "id" => $row['id'],
                "question" => $row['question'],
                "tally1" => $row['tally1'],
                "tally2" => $row['tally2'],
                "tally3" => $row['tally3'],
                "tally4" => $row['tally4']
            ];
            header('Content-Type: application/json');
            echo json_encode($json);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
    }
?>