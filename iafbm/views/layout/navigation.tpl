<?php

$navigation = array(
    'Personnes' => array(
        'personnes' => array(
            'url' => u('personnes'),
            'label' => 'Gérer des personnes'
        ),
        'candidats' => array(
            'url' => u('candidats'),
            'label' => 'Gérer des candidats'
        ),
    ),
    'Commissions' => array(
        'commissions' => array(
            'url' => u('commissions'),
            'label' => 'Gérer des commissions'
        ),
        'commissions-types' => array(
            'url' => u('commissions_types'),
            'label' => 'Gérer des types commissions'
        ),
    ),
    'Autres' => array(
        'titres-academiques' => array(
            'url' => u('titres_academiques'),
            'label' => 'Gérer les titres academiques'
        ),
        'fonctions-hospitalieres' => array(
            'url' => u('fonctions_hospitalieres'),
            'label' => 'Gérer les fonctions hospitalières'
        ),
        'departements' => array(
            'url' => u('departements'),
            'label' => 'Gérer les départements'
        ),
    )
);

$controller = @xContext::$router->params['xcontroller'];
?>


<div id="navigation">
<?php foreach ($navigation as $section => $items): ?>
  <h1><?php echo $section ?></h1>
  <ul>
<?php foreach ($items as $id => $item): ?>
    <li<?php if ($controller==$id) echo ' class="selected"' ?>>
      <a href="<?php echo $item['url'] ?>">
        <?php echo $item['label'] ?>
      </a>
    </li>
<?php endforeach ?>
  </ul>
<?php endforeach ?>
</div>