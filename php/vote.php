<!-- JM, 08/15/2026 -->
<?php
    $dev = true;
    $staging = false;

    $database_link = '../php/database.php';
    if ($dev) $database_link = './php/sqlite/connect.php';
    if ($staging) $database_link = '../../php/database.php';
    
    require_once($database_link);
?>

<?php
    if (empty($_POST['choice'])) {
        $select = "SELECT id, question, choices FROM `vote` ORDER BY id DESC LIMIT 1;";
        $row = $pdo->query($select)->fetchAll()[0];

        $id = $row['id'];
        $question = $row['question'];
        $choices = explode(",", $row['choices']);

?>
        <form action="<?=$_SERVER['PHP_SELF']?>#vote" method="POST">
            <fieldset>
                <legend class="question-box"><?= $question ?></legend>
                <div class="choices"> <!-- USE <ul>? -->
                    <?php foreach ($choices as $i => $choice) { ?>
                        <label tabindex="0">
                            <input type="radio" id="choice--<?=$i + 1?>" class="voting-radio" name="choice" value="<?=$i + 1?>" required>
                            <?=$choice?>
                        </label>
                    <?php } ?>
                </div> <!-- end .choices -->
                <input type="submit" class="submit" value="Submit">
            </fieldset>
        </form>
        <footer>
            <p>Privacy is crucial in all aspects of life, including voting. Your vote is completely confidential.</p>
        </footer>
<?php 
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_choice = (int) htmlspecialchars($_POST['choice']);

        $select = "SELECT * FROM vote
                   ORDER BY id DESC LIMIT 1;";
        $row = $pdo->query($select)->fetchAll()[0];

        $id = $row['id'];
        $question = $row['question'];
        $choices = explode(",", $row['choices']);

        if (!is_null($row["tally{$user_choice}"])) {
            $update = "UPDATE vote 
                       SET tally{$user_choice} = tally{$user_choice} + 1 
                       WHERE id = $id;";
            $pdo->exec($update);
        }
        $row["tally{$user_choice}"] += 1;
        $user_choice = $choices[$user_choice];

        $tallies = [];
        foreach ($choices as $i => $choice) {
            $num = $i + 1;
            $tallies[$choice] = $row["tally{$num}"];
        }
        $num_choices = count($choices);

        $tally_max = max($tallies);
        $tally_sum = array_sum($tallies);
?>
        <p>Thank you for participating! Your vote has been cast.</p>
        <figure class="vote-graph">
            <div class="vote-graph__graph"> <!-- USE p5.js TO RENDER OR <table>? -->
                <div class="plurality" data-majority="<?=round((($tally_max / $tally_sum) * 100), 2)?>%" style="--plurality: <?=round((100 - ($tally_max / $tally_sum) * 100), 2)?>%;"></div> <!-- end .plurality -->
                <div class="plurality" data-majority="<?=round((100 / $num_choices), 2)?>%" style="--plurality: <?=round(100 - (100 / $num_choices), 2)?>%;"></div> <!-- end .plurality -->
                <?php foreach ($tallies as $choice => $tally) { ?>
                    <!-- USE <meter> --><div 
                    class="vote-graph__bar<?= ($tally == $tally_max) ? ' winning' : '' ?><?= ($choice == $user_choice) ? ' user-choice' :  '' ?>"
                    style="--tally: <?=(($tally / $tally_sum) * 100)?>%;"
                    data-choice="<?=$choice?>"
                    data-tally="<?=$tally?>">
                    </div> <!-- end .vote-graph__bar -->
                <?php } ?>
            </div>
            <figcaption class="vote-graph__legend">
                <ul>
                    <li><div class="vote-graph__legend__color winning"></div> <!-- end .vote-graph__legend__color --> - Leading</li>
                    <li><div class="vote-graph__legend__color user-choice"></div> <!-- end .vote-graph__legend__color --> - Your Choice</li>
                </ul>
            </figcaption>
        </figure>
<?php } ?>