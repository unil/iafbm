<?php

$navigation = array(
    'Personnes' => array(
        'personnes' => array(
            'url' => u('personnes'),
            'label' => 'Gestion des personnes'
        ),
        'candidats' => array(
            'url' => u('candidats'),
            'label' => 'Gestion des candidats'
        ),
    ),
    'Commissions' => array(
        'commissions' => array(
            'url' => u('commissions'),
            'label' => 'Gestion des commissions'
        ),
        'commissions_types' => array(
            'url' => u('commissions_types'),
            'label' => 'Type de commissions'
        ),
    ),
    'Evaluations' => array(
         'evaluations' => array(
            'url' => u('evaluations'),
            'label' => 'Evaluations'
         ),
    ),
    'Autres' => array(
        'personnes-export' => array(
            'url' => u('personnes/do/export'),
            'label' => 'Export des personnes (CSV)'
        ),
        'activites' => array(
            'url' => u('activites'),
            'label' => 'Catalogue des fonctions'
        )
    )
);

$controller = @xContext::$router->params['xcontroller'];
?>


<div id="navigation">
<?php foreach ($navigation as $section => $items): ?>
<div class="box">
  <h1><?php echo $section ?></h1>
  <div>
  <ul>
<?php foreach ($items as $id => $item): ?>
    <li<?php if ($controller==$id) echo ' class="selected"' ?>>
      <a href="<?php echo $item['url'] ?>">
        <?php echo $item['label'] ?>
      </a>
    </li>
<?php endforeach ?>
  </ul>
  </div>
  </div>
<?php endforeach ?>
</div>