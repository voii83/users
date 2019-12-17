<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'string'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            if (!$this->getUser()) {
                $user = new User();
                $user->username = $this->username;
                $user->save();
            }
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    public function getUser()
    {
        return User::findByUsername($this->username);
    }
}
