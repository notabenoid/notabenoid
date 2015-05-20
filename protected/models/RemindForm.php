<?php

class RemindForm extends CFormModel
{
    public $clue;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            // login and pass are required
            ['clue', 'required', 'message' => 'Сюда нужно что-нибудь написать.'],
            ['clue', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'clue' => 'Введите Ваш логин или e-mail, который указывали при регистрации, и пароль будет отправлен вам по электронной почте:',
        ];
    }

    public function findUser()
    {
        if (strpos($this->clue, '@') !== false) {
            $user = User::model()->find('LOWER(email) = :email', [':email' => mb_strtolower($this->clue)]);
            if (!$user) {
                $this->addError('clue', 'Пользователей с таким адресом электронной почты не зарегистрировано.');
            }
        } else {
            $user = User::model()->find('LOWER(login) = :login', [':login' => mb_strtolower($this->clue)]);
            if (!$user) {
                $this->addError('clue', 'Пользователей с таким логином не зарегистрировано.');
            }
        }

        return $user;
    }
}
