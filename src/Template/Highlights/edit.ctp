<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Highlight $highlight
 */

$this->start('css');
?>
<style>
    .control {
        padding-right: 30px !important;
    }
</style>
<?php
$this->end();
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $highlight->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $highlight->id)]
            )
            ?></li>
        <li><?= $this->Html->link(__('List Highlights'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="highlights form large-9 medium-8 columns content">
    <?= $this->Form->create($highlight) ?>
    <fieldset>
        <legend><?= __('Edit Highlight') ?></legend>
        <?php
        echo $this->Form->control('professional_id', ['options' => $professionals, 'empty' => true]);
        echo $this->Form->control('subcategory_id', ['options' => $subcategories, 'empty' => true]);
        echo $this->Form->control('service_id', ['options' => $services, 'empty' => true]);
        echo $this->Form->control('finish', [
            'label' => 'Finish - Year/Month/Day Hour:Minute',
            'minYear' => date('Y'),
            'maxYear' => date('Y') + 10,
            'empty' => true,
            'year' => ['class' => 'control'],
            'month' => ['class' => 'control'],
            'day' => ['class' => 'control'],
            'hour' => ['class' => 'control'],
            'minute' => ['class' => 'control']
        ]);
        echo $this->Form->control('position');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>