<?php

class Tr_group extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'tr_groups';
    }

    public function relations()
    {
        return [
            'uid' => [self::BELONGS_TO, 'User', 'id'],
            'book_id' => [self::BELONGS_TO, 'Book', 'id'],
        ];
    }

    public $uid, $book_id, $flags;
}
