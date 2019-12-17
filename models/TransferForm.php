<?php

namespace app\models;

use Yii;
use yii\base\Model;

class TransferForm extends Model
{
    const MIN_USER_BALANCE = -1000;

    public $transfer;
    private $errors;

    public function rules()
    {
        return [
            [['transfer'], 'required'],
            ['transfer', 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'transfer' => 'Transfer',
        ];
    }

    public function save($user)
    {
        $error = [];
        $userFor = User::findOne($user);
        $userCurrent = User::findOne(Yii::$app->user->getId());

        if (!$userCurrent || !$userCurrent) {
            return $error = [
                'error' => 'error users',
            ];
        }

        if (($userCurrent->balance - $this->transfer) <= self::MIN_USER_BALANCE) {
            return $error = [
                'error' => 'balance cannot be less than ' . self::MIN_USER_BALANCE,
            ];
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $userFor->balance += $this->transfer;
            $userCurrent->balance -= $this->transfer;
            $userCurrent->save();
            $userFor->save();
            $transaction->commit();

        } catch (\Exception $e) {
            $transaction->rollBack();
        }

        return $error;
    }
}