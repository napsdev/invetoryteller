<?php if (!empty($tablesearch)): ?>
    <?php
    $total_neto_sum = 0;
    $total_revenue_sum = 0;
    ?>
    <table border='1'>
        <tr>
            <th>Mes</th>
            <th>Forma de Pago</th>
            <th>Total Neto</th>
            <th>Ganancia</th>
        </tr>
        <?php foreach ($tablesearch as $row): ?>
            <?php
            $total_neto_sum += $row['total_neto'];
            $total_revenue_sum += $row['total_revenue'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['dia']) ?></td>
                <td><?= htmlspecialchars($row['forma_pago']) ?></td>
                <td><?= htmlspecialchars(number_format($row['total_neto'])) ?></td>
                <td><?= htmlspecialchars(number_format($row['total_revenue'])) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong><?= number_format($total_neto_sum, 2) ?></strong></td>
            <td><strong><?= number_format($total_revenue_sum, 2) ?></strong></td>
        </tr>
    </table>
<?php elseif (!empty($table)): ?>
    <?php
    $total_neto_sum = 0;
    ?>
    <table border='1'>
        <tr>
            <th>Mes</th>
            <th>Forma de Pago</th>
            <th>Total Neto</th>
        </tr>
        <?php foreach ($table as $row): ?>
            <?php
            $total_neto_sum += $row['total_neto'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['mes']) ?></td>
                <td><?= htmlspecialchars($row['forma_pago']) ?></td>
                <td><?= htmlspecialchars(number_format($row['total_neto'])) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong><?= number_format($total_neto_sum, 2) ?></strong></td>
        </tr>
    </table>
<?php else: ?>
    <p>No hay resultados.</p>
<?php endif; ?>
