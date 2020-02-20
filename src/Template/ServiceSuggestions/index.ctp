<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ServiceSuggestion[]|\Cake\Collection\CollectionInterface $serviceSuggestions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Service Suggestion'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="serviceSuggestions index large-9 medium-8 columns content">
    <h3><?= __('Service Suggestions') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Professionals.name', 'Professional') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Subcategories.description', 'Subcategory') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Subcategories.Categories.description', 'Category') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($serviceSuggestions as $serviceSuggestion): ?>
            <tr>
                <td><?= $this->Number->format($serviceSuggestion->id) ?></td>
                <td><?= h($serviceSuggestion->title) ?></td>
                <td><?= $serviceSuggestion->has('professional') ? $this->Html->link($serviceSuggestion->professional->name, ['controller' => 'Professionals', 'action' => 'view', $serviceSuggestion->professional->id]) : '' ?></td>
                <td><?= $serviceSuggestion->has('subcategory') ? $this->Html->link($serviceSuggestion->subcategory->description, ['controller' => 'Subcategories', 'action' => 'view', $serviceSuggestion->subcategory->id]) : '' ?></td>
                <td><?= $serviceSuggestion->has('subcategory') ? $this->Html->link($serviceSuggestion->subcategory->category->description, ['controller' => 'Categories', 'action' => 'view', $serviceSuggestion->subcategory->category->id]) : '' ?></td>
                <td><?= h($serviceSuggestion->created->i18nFormat('dd/MM/yyyy HH:mm')) ?></td>
                <td class="actions">
                    <?= $this->Form->postLink(__('Accept'), ['action' => 'accept', $serviceSuggestion->id], ['confirm' => __('Are you sure you want to accept # {0}?', $serviceSuggestion->id)]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $serviceSuggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $serviceSuggestion->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
