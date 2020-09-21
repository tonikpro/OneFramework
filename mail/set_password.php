<?php
$layout = 'main';
use etc\app\App;
?>
<div class="verify-email">
    <p>Приветствуем <?php echo $user->fullname ?>,</p>
    <p>Ваш логин: <?php echo $user->login ?></p>
    <p>Ваш пароль: <?php echo $user->password ?></p>
</div>
