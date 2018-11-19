<link rel="stylesheet" href="style.css" type="text/css"/>
<form method="post">
    <input type="submit" value="<?= $control->getButtonTitle() ?>">
</form>

<table>
    <tr>
        <th>Start</th>
        <th>End</th>
        <th>Total</th>
    </tr>
    <?php foreach($work['table'] as $row):?>
        <tr>
            <?php foreach($row as $col):?>
                <td>
                    <?= $col; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td>Total:</td>
        <td><?= $work['sum'] ?></td>
    </tr>
</table>

<div>
    <table>
        <tr>
            <th>Start</th>
            <th>End</th>
            <th>Total</th>
        </tr>
        <?php foreach($rest['table'] as $row):?>
            <tr>
                <?php foreach($row as $col):?>
                    <td>
                        <?= $col; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td>Total:</td>
            <td><?= $rest['sum'] ?></td>
        </tr>
    </table>
</div>