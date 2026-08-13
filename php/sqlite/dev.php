<?php

    if ($_POST['action'] === 'insert') require_once('insert-row.php');

?><!DOCTYPE html>
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