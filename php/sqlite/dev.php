<?php

    if ($_POST['action'] === 'insert') require_once('./commands/insert-row.php');
    if ($_POST['action'] === 'set') require_once('./commands/set-row.php');
    if ($_POST['action'] === 'reset') require_once('./commands/reset-row.php');

?><!DOCTYPE html>
<!-- JM, 08/18/26 -->
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Development Console</title>
        <style>
            fieldset {
                display: flex;
                flex-direction: column;
                align-items: start;
                gap: 0.5em;
            }
            ul {
                list-style-type: none;
                padding: 0;
                margin: 0;
            }
            .plus-minus-container {

            }
        </style>
    </head>
    <body>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <fieldset>
                <legend>Insert Row</legend>

                <span><input type="text" name="question" placeholder="Question?">?</span>
                <ul class="choices-container">
                    <template class="template" id="choice">
                        <li class="li"><input type="text" name="choices[]" class="choices" placeholder="Choice"></li>
                    </template>
                    <li class="li"><input type="text" name="choices[]" class="choices" placeholder="Choice"></li>
                    <li class="li"><input type="text" name="choices[]" class="choices" placeholder="Choice"></li>
                    <li class="plus-minus-container">
                        <button type="button" class="plus-minus minus" id="minus-choice">&minus;</button>
                        <button type="button" class="plus-minus plus" id="plus-choice">&plus;</button>
                    </li>
                </ul>
                
                <input type="hidden" name="action" value="insert">
                <input type="submit" value="Insert">
            </fieldset>
        </form>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <fieldset>
                <legend>Set Row</legend>

                <select name="row" id="set-row">
                    <?php
                        $command = true;
                        require_once('connect.php');
                        $select = "SELECT * FROM vote;";
                        $rows = $pdo->query($select)->fetchAll();
                        $row1 = $rows[0];
                        foreach ($rows as $col):
                    ?>
                        <option value="<?=$col['id']?>"><?=$col['id']?>: <?=$col['question']?></option>
                    <?php endforeach; ?>
                </select>
                
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <input 
                        type="number" 
                        name="tally<?=$i?>" 
                        class="tally" 
                        id="tally<?=$i?>" 
                        <?=(!is_null($row1["tally$i"]) || $row1["tally$i"] === 0) ? '' : 'disabled'?>
                        value="<?=$row1["tally$i"]?>">
                <?php endfor; ?>

                <input type="hidden" name="action" value="set">
                <input type="submit" value="Set">
            </fieldset>
            <script>
                const select = document.querySelector("#set-row");
                
                const tallyEls = document.querySelectorAll(".tally");

                select.addEventListener("change", (e) => {
                    fetch(`./commands/get-row.php?row=${select.value}`, { headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }})
                    .then((res) => res.json())
                    .then((json) => {
                        tallyEls.forEach((tal) => {
                            tal.value = json[tal.id];
                            tal.disabled = (!json[tal.id] && json[tal.id] !== 0);
                        });
                    });
                });
            </script>
        </form>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <fieldset>
                <legend>Reset Row</legend>

                <select name="row" id="reset-row">
                    <?php
                        $command = true;
                        require_once('connect.php');
                        $select = "SELECT id, question FROM vote;";
                        $rows = $pdo->query($select)->fetchAll();
                        foreach ($rows as $col):
                    ?>
                    <option value="<?=$col['id']?>"><?=$col['id']?>: <?=$col['question']?></option>
                    <?php endforeach; ?>
                </select>
                
                <input type="hidden" name="action" value="reset">
                <input type="submit" value="Reset">
            </fieldset>
        </form>

        <script>
            function addTemplate(e) {
                e.preventDefault();

                const lis = e.target.parentNode.parentNode.querySelectorAll(".li");
                let last;
                if (lis.length === 0) {
                    last = e.target.parentNode.parentNode.querySelector("template");
                } else {
                    last = lis[lis.length - 1];
                }

                if (e.target.parentNode.parentNode.classList.contains("choices-container") && lis.length === 4) return;

                const template = e.target.parentNode.parentNode.querySelector("template");
                const li = template.content.cloneNode(true).querySelector("li");
                last.after(li);
            }

            function removeTemplate(e) {
                e.preventDefault();

                const lis = e.target.parentNode.parentNode.querySelectorAll(".li");
                if ((e.target.parentNode.parentNode
                    .classList.contains("choices-container") 
                    && lis.length === 2) 
                    || lis.length === 0) return;

                const last = lis[lis.length - 1];
                last.remove();
            }

            document.querySelectorAll(".plus-minus.minus").forEach((minus) => {
                minus.addEventListener("click", (e) => removeTemplate(e));
            });
            document.querySelectorAll(".plus-minus.plus").forEach((plus) => {
                plus.addEventListener("click", (e) => addTemplate(e));
            });
        </script>
    </body>
</html>