<h1>Чат с администратором</h1>
<?php
use \yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\helpers\Html;

?>

<div class="container">
    <img src="<?=$administrator->getImage()?>" width="70" height="80">
    <br>
    <div class="messages" id = "mes">
        <?php if (!empty($messages)):?>
            <?php foreach ($messages as $message) :?>
                <br>
                <?php if ($message->id_sender == $administrator->id):?>
                    Администратор(<?=$message->user->name?>) :
                <?php else :?>
                    Вы :
                <?php endif;?>
                <?=$message->text?>
            <?php endforeach;?>
        <?php endif;?>
    </div>

    <?php
    $form = ActiveForm::begin();
    ?>
    <?=$form->field($model, 'text')->textarea();?>

    <div>
        <button type="submit" class = "btn btn-success">Отправить</button>
    </div>
    <?php
    ActiveForm::end();
    ?>
</div>
</body>
</div>

