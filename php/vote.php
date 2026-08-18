<!-- JM, 08/18/2026 -->
<?php
    $dev = true;
    $staging = false;

    $database_link = '../php/database.php';
    if ($dev) $database_link = './php/sqlite/connect.php';
    if ($staging) $database_link = '../../php/database.php';
    
    require_once($database_link);
?>

<?php
    if (empty($_POST['choice'])):
        $select = "SELECT id, question, choices FROM vote ORDER BY id DESC LIMIT 1;";
        $row = $pdo->query($select)->fetchAll()[0];

        $id = $row['id'];
        $question = $row['question'];
        $choices = explode(",", $row['choices']);

?>
        <form action="<?=$_SERVER['PHP_SELF']?>#vote" method="POST">
            <fieldset>
                <legend class="question-box"><?= $question ?></legend>
                <ul class="choices">
                    <?php foreach ($choices as $i => $choice) { ?>
                        <li>
                            <label tabindex="0">
                                <input type="radio" id="choice--<?=$i + 1?>" class="voting-radio" name="choice" value="<?=$i + 1?>" required>
                                <?=$choice?>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
                <input type="submit" class="submit" value="Submit">
            </fieldset>
        </form>
<?php 
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST'):
        $user_choice = (int) htmlspecialchars($_POST['choice']);

        $select = "SELECT * FROM vote
                   ORDER BY id DESC LIMIT 1;";
        $row = $pdo->query($select)->fetchAll()[0];

        $id = $row['id'];
        $question = $row['question'];
        $choices = explode(",", $row['choices']);

        if (!is_null($row["tally$user_choice"])) {
            $update = "UPDATE vote 
                       SET tally{$user_choice} = tally{$user_choice} + 1 
                       WHERE id = $id;";
            $pdo->exec($update);
        }
        $row["tally$user_choice"] += 1;
        $user_choice = $choices[$user_choice - 1];

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
            <ul class="vote-graph__graph">
                <?php
                    $i = 1;
                    foreach ($tallies as $choice => $tally):
                ?>
                    <li 
                        class="vote-graph__col" 
                        data-choice="<?=$choice?>"
                        data-tally="<?=$tally?> vote<?=(($tally === 1) ? '' : 's')?>: <?=round((($tally / $tally_sum) * 100), 1)?>%">
                        <meter
                            class="vote-graph__bar<?= ($tally == $tally_max) ? ' winning' : '' ?><?= ($choice == $user_choice) ? ' user-choice' : '' ?>"
                            value="<?=(($tally / $tally_sum) * 100)?>"
                            min="0"
                            max="100">
                            <?=round((($tally / $tally_sum) * 100), 2)?>%
                        </meter>
                    </li>
                <?php endforeach; ?>
            </ul>
            <figcaption class="vote-graph__legend">
                <ul>
                    <li><div class="vote-graph__legend__color winning"></div> <!-- end .vote-graph__legend__color --> - Leading</li>
                    <li><div class="vote-graph__legend__color user-choice"></div> <!-- end .vote-graph__legend__color --> - Your Choice</li>
                </ul>
            </figcaption>
        </figure>
<?php endif; ?>
<footer>
    <p>Privacy is crucial in all aspects of life, including voting. Your vote is completely confidential.</p>
</footer>