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
<?php
$script = <<< JS
    $(document).ready(function() {
        setInterval(function(){ $("#refreshButton").click(); console.log("a");}, 1000);
    });
JS;
$this->registerJs($script);
?>
    <?php Pjax::begin(); ?>
    <?= Html::a(
        'Обновить',
        ['index'],
        ['class' => 'btn btn-lg btn-primary', 'id' => 'refreshButton']
    ) ?>

<?php Pjax::end(); ?>
<div id = "d"></div>
<input type="text" name="title">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
    var mes = document.getElementById("mes");
    mes.scrollTop = mes.scrollHeight;
        var d = document.getElementById("d");
        setInterval(function () {
            d.innerHTML = "";
            d.innerHTML = <?php foreach ($messages as $message) :?>
                <br>
                <?=$message->text?>
            <?endforeach;?>
        }, 2000);


</script>
</body>
</div>

