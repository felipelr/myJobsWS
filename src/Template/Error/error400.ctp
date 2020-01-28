<?php

use Cake\Error\Debugger;

$this->layout = 'error';

?>
<div>
    <?php
    if ($code == 401) {
    ?>
        <h2>Não Autorizado</h2>
        <p class="error">
            <strong><?= __d('cake', 'Error') ?>: </strong>
            <?= h($message) ?>
        </p>
        <?= $this->Html->link('Ir para o Login', ['controller' => 'Users', 'action' => 'signin']) ?>
    <?php
    } else {
    ?>
        <h2><?= __d('cake', 'An Error Has Occurred') ?></h2>
        <p class="error">
            <strong><?= __d('cake', 'Error') ?>: </strong>
            <?= h($message) ?>
        </p>
        <?= $this->Html->link(__('Back'), 'javascript:history.back()') ?>
    <?php
    }
    ?>
</div>