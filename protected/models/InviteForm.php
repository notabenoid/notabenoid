<?php

class InviteForm extends CFormModel
{
    public $email, $who;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            // login and pass are required
            ['email', 'required', 'message' => 'Пожалуйста, введите адрес вашего друга.'],
            ['email', 'email', 'message' => 'Это не похоже на адрес электронной почты, проверьте ещё раз.'],
            ['who', 'required', 'message' => 'Пожалуйста, подпишитесь.'],
            ['who', 'filter', 'filter' => 'htmlspecialchars'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Введите e-mail Вашего друга, и ему отправится красивое приглашение с Вашими данными:',
            'who' => 'Как Вас представить?',
        ];
    }
}
