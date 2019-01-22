<html>
<head>
    <link rel="stylesheet" href="style.css" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="/js/script.js"></script>

</head>
<body>

<?php
    /*if (!isset($errorMsg)) {
        echo $errorMsg;
        exit;
    }*/
?>

    <table class="my">
        <tr class="my">
            <th>Start</th>
            <th>End</th>
            <th>Total</th>
        </tr>
        <?php foreach ($work['table'] as $row): ?>
            <tr>
                <?php foreach ($row as $col): ?>
                    <td>
                        <?= $col; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>

        <tr class="total">
            <td>Total:</td>
            <td colspan="2" align="center"><?= $work['sum'] ?></td>
        </tr>
    </table>

    <table class="my">
        <tr class="my">
            <th>Start</th>
            <th>End</th>
            <th>Total</th>
        </tr>
        <?php foreach ($rest['table'] as $row): ?>
            <tr class="my">
                <?php foreach ($row as $col): ?>
                    <td>
                        <?= $col; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        <tr class="total">
            <td>Total:</td>
            <td colspan="2" align="center"><?= $rest['sum'] ?></td>
        </tr>
    </table>


    <form method="post" class="form">
        <input type="submit" value="<?= $control->getButtonTitle() ?>">
    </form>
    <!-- <p>Date: <input type="text" id="datepicker"></p> -->
    <input type="date" id="start" name="trip-start" value="">
</body>
</html>