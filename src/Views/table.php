<?php if (!empty($tablesearch)): ?>
    <table border='1'>
            <tr>
                <th>Mes</th>
                <th>Forma de Pago</th>
                <th>Total Neto</th>
                <th>Ganancia</th>
            </tr>
                <?php foreach ($tablesearch as $row): ?>
                    <tr>
                        <td><?=htmlspecialchars($row['dia'])?></td>
                        <td><?=htmlspecialchars($row['forma_pago'])?></td>
                        <td><?=htmlspecialchars($row['total_neto'])?></td>
                        <td><?=htmlspecialchars($row['total_revenue'])?></td>
                    </tr>
                <?php endforeach; ?>
    </table>
<?php elseif (!empty($table)): ?>
    <table border='1'>
            <tr>
                <th>Mes</th>
                <th>Forma de Pago</th>
                <th>Total Neto</th>
            </tr>
                <?php foreach ($table as $row): ?>
                    <tr>
                        <td><?=htmlspecialchars($row['mes'])?></td>
                        <td><?=htmlspecialchars($row['forma_pago'])?></td>
                        <td><?=htmlspecialchars($row['total_neto'])?></td>
                    </tr>
                <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No hay resultados.</p>
<?php endif; ?>

