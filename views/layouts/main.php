<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\PublicAsset;
use yii\helpers\Url;



PublicAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar main-menu navbar-default">
    <div class="container">
        <div class="menu-content">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav text-uppercase">
                    <li>
                        <a href="<?=Yii::$app->homeUrl?>">Home</a>
                    </li>
                    <li>
                        <?php if(Yii::$app->user->identity->isAdmin) :?>
                            <a href="/project/web/admin/">Админка</a>
                        <?php endif;?>
                    </li>
                </ul>
                <div class="i_con">
                    <ul class="navbar-nav navbar-right">
                        <?php
                        if (Yii::$app->user->isGuest) :?>
                            <li><a href="<?=Url::toRoute(['auth/login'])?>">Login</a></li>
                            <li><a href="<?=Url::toRoute(['auth/signup'])?>">Register</a></li>
                        <?php else :?>
                            <?= Html::beginForm(['/auth/test'], 'post')
                            . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->name . ')',
                                ['class' => 'btn btn-link logout']
                            )
                            . Html::endForm() ?>

                            <img src="<?=Yii::$app->user->identity->getImage()?>" width="70" height="50" alt="">

                            <li><a href="<?=Url::toRoute(['chat/index'])?>">Чат с администраторм</a></li>
                            <li>
                                <a href="site/add-image">Добаваить фтоографию</a>
                            </li>
                        <?php endif;?>
                    </ul>

                </div>
                <!-- /.navbar-collapse -->
            </div>
        </div>

<?=$content?>        <!-- /.container-fluid -->
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
