<?php
/**
 * @var \App\View\AppView $this
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Services'), ['controller' => 'Services', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Highlights'), ['controller' => 'Highlights', 'action' => 'index']) ?></li>
    </ul>
</nav>
<div class="index large-9 medium-8 columns content">
    <h3><?= __('Home') ?></h3>
</div>
