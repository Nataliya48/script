<link rel="stylesheet" href="style.css" type="text/css"/>

<table>
    <tr>
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

<table>
    <tr>
        <th>Start</th>
        <th>End</th>
        <th>Total</th>
    </tr>
    <?php foreach ($rest['table'] as $row): ?>
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
        <td colspan="2" align="center"><?= $rest['sum'] ?></td>
    </tr>
</table>

<form method="post" class="form">
    <input type="submit" value="<?= $control->getButtonTitle() ?>">
</form>