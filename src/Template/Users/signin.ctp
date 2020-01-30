<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div>
    <h1>Login</h1>
    <?php
    echo $this->Form->create(null, ['url' => '/users/signin']);
    ?>
    Usuário: <input name="email" placeholder="Digite o seu usuário" type="text" />
    Senha: <input name="password" placeholder="Digite a sua senha" type="password" />
    <button type="submit">Entrar</button>
    <?php
    echo $this->Form->end(); ?>
</div>