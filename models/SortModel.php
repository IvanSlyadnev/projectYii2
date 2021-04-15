<?php
namespace app\models;
use yii\base\Model;
use Yii;

class SortModel extends Model {
    public $chouses =[
        'По алфавиту',
        'По кол-ву просмотров',
        'По длине загаловка'
    ];

    public $index = 0;


}