<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    const ERROR_USER_DELETED = 200;
    const ERROR_USER_INACTIVE = 201;

    public function authenticate()
    {
        $record = User::model()->byLogin($this->username)->find();
        if ($record === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!password_verify($this->password, $record->pass)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } elseif ($record->sex == '-') {
            $this->errorCode = self::ERROR_USER_DELETED;
        } elseif (!$record->can(User::CAN_LOGIN)) {
            $this->errorCode = self::ERROR_USER_INACTIVE;
        } else {
            $this->_id = $record->id;
            $this->setState('login', $record->login);
            $this->setState('email', $record->email);
            $this->setState('sex', $record->sex);
            $this->setState('ini', $record->ini);
            $this->setState('can', $record->can);
            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}
