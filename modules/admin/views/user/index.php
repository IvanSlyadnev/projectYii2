<?php
use yii\helpers\Url;
?>
<h1>Пользователи</h1>
<?php if (!empty($users)) :?>
    <table border="1">
        <thead>
            <td>Имя пользователия</td>
            <td>Фото</td>
        </thead>
        <tbody>
            <?php foreach ($users as $user) :?>
                <tr>
                    <td><a href="<?=Url::toRoute(['user/view', 'id'=>$user->id])?>"><?=$user->name?></a></td>
                    <td><a href="<?=Url::toRoute(['user/view', 'id'=>$user->id])?>">
                            <img src="<?=$user->getImage();?>" width="70" height="80">
                        </a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>

<?php endif;?>
