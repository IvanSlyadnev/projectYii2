<?php

namespace app\models;
use Yii;

class Message extends \yii\db\ActiveRecord
{
    function rules()
    {
        return [
            [['text'], 'required']
        ];
    }
    public function saveMessage($id){
        $this->id_getter = $id;
        $this->id_sender = Yii::$app->user->identity->id;
        $this->date = date('Y-m-d H:i:s');
        return $this->save(false);
    }
    public function getDate() {
        return Yii::$app->formatter->asDate($this->date);
    }

    /*public function sortMessages($messages) {
        return usort($messages, 'sortDate_');
    }

     /*static function sortDate_($m1, $m2) {
        if ($m1['date'] == $m2['date']) {
            return 0;
        }
        return ($m1['date'] < $m2['date']) ? 1 : 0;
    }*/



    public function getUser(){
        return $this->hasOne(User::className(), ['id' =>'id_sender']);
    }
}