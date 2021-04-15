<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<h1>Загрузка изображения</h1>
<?php $form = ActiveForm::begin();?>

<?=$form->field($model, 'image')->fileInput()?>
<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end();?>
