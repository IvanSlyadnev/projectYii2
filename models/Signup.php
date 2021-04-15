<?php
namespace app\models;

use yii\base\Model;
use Yii;

class Signup extends Model
{
    public $name;
    public $email;
    public $password;
    public $photo;

    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['name'], 'string'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' =>'app\models\User', 'targetAttribute' => 'email']
        ];
    }

    public function signup($fileName) {
        if ($this->validate()) {
            $user = new User();
            $user->attributes = $this->attributes;
            //вместо того чтоб прописывать  $user->name = $this->name и т.д

            if ($fileName !== null) $user->saveImage($fileName);
            $user->create();
            $this->login();
            return true;
        }
    }


    public function getUser() {
        return User::findUserByName($this->name);
    }

    public function login() {
        return Yii::$app->user->login($this->getUser());
    }
}