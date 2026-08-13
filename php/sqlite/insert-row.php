<?php
    if (!empty($_POST['question'] && !empty($_POST['choices']))) {
        $command = true;
        require_once('connect.php');

        $question = trim($_POST['question']);
        $question = filter_var($question, FILTER_SANITIZE_SPECIAL_CHARS);
        if (!str_ends_with($question, '?')) $question .= '?';
        
        $choices = ($_POST['choices']);
        $choices = filter_var_array($choices, FILTER_SANITIZE_SPECIAL_CHARS);
        
        $num_choices = count($choices);
        $choices = implode(',', $choices);

        $variables = [
            ":question" => $question,
            ":choices" => $choices,
        ];

        $tally = '';
        $tally_var = '';
        for ($i = 1; $i <= $num_choices; $i++) {
            $tally .= "tally$i";
            $tally_var .= ":tally$i";
            $variables[":tally$i"] = 0;
            if ($i != $num_choices) {
                $tally .= ", ";
                $tally_var .= ", ";
            }
        }

        $insert = "INSERT INTO vote(question, choices, $tally) VALUES(
            :question, :choices, $tally_var
        )";

        try {
            $stmt = $pdo->prepare($insert);
            $stmt->execute($variables);

        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            echo $error_message;
            exit();
        }
    }
?>