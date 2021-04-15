<?php
use \yii\widgets\ActiveForm;

?>
<h1>Пользователь</h1>
<div class="container">
    <?=$user->name;?>
    <img src="<?=$user->getImage()?>" width="70" height="80">
    <br>
    <div class="messages" id = "mes">
        <?php if (!empty($messages)) :?>
            <?php foreach ($messages as $message) :?>
                <br>
                <?php if ($message->user->id == $user->id) :?>
                    <?=$message->user->name;?> :
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
<script type="text/javascript">
    var mes = document.getElementById("mes");
    mes.scrollTop = mes.scrollHeight;
</script>
</body>
</div>
