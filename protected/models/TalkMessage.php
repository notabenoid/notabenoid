<?php

class TalkMessage extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function tableName()
    {
        return 'talk_messages';
    }

    public $talk_id, $user_id, $cdate, $message;

    public function rules()
    {
        return [
            ['message', 'required'],
        ];
    }

    public function relations()
    {
        return [
            'talk' => [self::BELONGS_TO, 'Talk', 'talk_id'],
            'user' => [self::BELONGS_TO, 'User', 'user_id', 'select' => ['id', 'login', 'sex', 'upic']],
            'owner' => [self::BELONGS_TO, 'User', 'user_id', 'select' => ['id', 'login', 'sex', 'email', 'upic', 'ini']],
        ];
    }

    public function talk($talk_id)
    {
        $c = $this->getDbCriteria();
        $c->addCondition("talk_id = '{$talk_id}'");

        return $this;
    }
}
