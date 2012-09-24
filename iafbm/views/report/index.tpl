<style>
    table td,
    table th {
        padding: 10px;
    }
</style>

<h1>Reporting</h1>

<div>
    <table style="width:100%">
        <tr>
            <th style="width:20%">
                Effectif corps enseignant
            </th>
            <td>
                <a href="<?php echo u('report/do/effectif-corps-enseignant') ?>">
                    <img style="vertical-align:top" src="<?php echo u('a/img/icons/page_white_put.png') ?>"/>
                    PDF
                </a>
                &nbsp;
                <a href="<?php echo u('report/do/effectif-corps-enseignant?html') ?>" target="_blank">
                    <img style="vertical-align:top" src="<?php echo u('a/img/icons/page_white_put.png') ?>"/>
                    HTML
                </a>
            </td>
        </tr>
        <tr>
            <th style="width:20%">
                Liste corps enseignant
            </th>
            <td>
                <a href="<?php echo u('report/do/liste-corps-enseignant') ?>">
                    <img style="vertical-align:top" src="<?php echo u('a/img/icons/page_white_put.png') ?>"/>
                    PDF
                </a>
                &nbsp;
                <a href="<?php echo u('report/do/liste-corps-enseignant?html') ?>" target="_blank">
                    <img style="vertical-align:top" src="<?php echo u('a/img/icons/page_white_put.png') ?>"/>
                    HTML
                </a>
            </td>
        </tr>
   </table>
</div>
