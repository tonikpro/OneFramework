<?php
// $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
$layout = 'main';
use etc\app\App;
$verifyLink = App::i()->urlManager->createAbsoluteUrl('user/set-password', ['token' => $model->verification_code]);
?>
<div class="verify-email">
    <p>Приветствуем <?php echo $model->fullname ?>,</p>

    <p>Для смены пароля пройдите по ссылке:</p>

    <p><?= $verifyLink ?></p>
</div>
