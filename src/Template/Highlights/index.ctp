<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Highlight[]|\Cake\Collection\CollectionInterface $highlights
 */

$this->start('css');
?>
<style>
    .row {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
</style>
<?php
$this->end();
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Highlight'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="highlights index large-9 medium-8 columns content">
    <h3><?= __('Highlights') ?></h3>
    <form>
        <div class="row">
            <div style="padding-right: 20px;">
                <input placeholder="Type here" name="search" type="text" value="<?= $search ?>" />
            </div>
            <div>
                <?= $this->Form->button(__('Search')) ?>
            </div>
        </div>
    </form>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Professionals.name', 'Professionals') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Subcategories.description', 'Subcategory') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Services.title', 'Service') ?></th>
                <th scope="col"><?= $this->Paginator->sort('position') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('finish') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($highlights as $highlight) : ?>
                <tr>
                    <td><?= $this->Number->format($highlight->id) ?></td>
                    <td><?= $highlight->has('professional') ? $this->Html->link($highlight->professional->name, ['controller' => 'Professionals', 'action' => 'view', $highlight->professional->id]) : '' ?></td>
                    <td><?= $highlight->has('subcategory') ? $this->Html->link($highlight->subcategory->description, ['controller' => 'Subcategories', 'action' => 'view', $highlight->subcategory->id]) : '' ?></td>
                    <td><?= $highlight->has('service') ? $this->Html->link($highlight->service->title, ['controller' => 'Services', 'action' => 'view', $highlight->service->id]) : '' ?></td>
                    <td><?= $this->Number->format($highlight->position) ?></td>
                    <td><?= h($highlight->created->i18nFormat('dd/MM/yyyy HH:mm')) ?></td>
                    <td><?= h($highlight->finish->i18nFormat('dd/MM/yyyy HH:mm')) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $highlight->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $highlight->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $highlight->id], ['confirm' => __('Are you sure you want to delete # {0}?', $highlight->id)]) ?>
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