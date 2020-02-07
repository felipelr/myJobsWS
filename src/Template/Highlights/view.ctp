<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Highlight $highlight
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Highlight'), ['action' => 'edit', $highlight->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Highlight'), ['action' => 'delete', $highlight->id], ['confirm' => __('Are you sure you want to delete # {0}?', $highlight->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Highlights'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Highlight'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="highlights view large-9 medium-8 columns content">
    <h3><?= h($highlight->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($highlight->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Professional') ?></th>
            <td><?= $highlight->has('professional') ? $this->Html->link($highlight->professional->name, ['controller' => 'Professionals', 'action' => 'view', $highlight->professional->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Subcategory') ?></th>
            <td><?= $highlight->has('subcategory') ? $this->Html->link($highlight->subcategory->description, ['controller' => 'Subcategories', 'action' => 'view', $highlight->subcategory->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Service') ?></th>
            <td><?= $highlight->has('service') ? $this->Html->link($highlight->service->title, ['controller' => 'Services', 'action' => 'view', $highlight->service->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Position') ?></th>
            <td><?= $this->Number->format($highlight->position) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($highlight->created->i18nFormat('dd/MM/yyyy HH:mm')) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Finish') ?></th>
            <td><?= h($highlight->finish->i18nFormat('dd/MM/yyyy HH:mm')) ?></td>
        </tr>
    </table>
</div>
