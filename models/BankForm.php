<?php

namespace app\models;

use yii\base\Model;

class BankForm extends Model
{
    public $bankId;

    public function rules()
    {
        return [
            ['bankId', 'required']
        ];
    }
}