<h1><?php echo $d['username'] ?></h1>

<table class="grid">
    <tr>
        <th>Roles</th>
        <td>a<?php echo implode(', ', $d['roles']) ?></td>
    </tr>
</table>