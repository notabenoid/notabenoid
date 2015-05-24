<?php

class Contribution extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user_tr_stat';
    }

    public $book_id, $user_id, $n_trs;

    public function primaryKey()
    {
        return ['user_id', 'book_id'];
    }

    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, 'User', 'user_id'],
            'book' => [self::BELONGS_TO, 'Book', 'book_id'],
        ];
    }

    public function user($user_id)
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 't.user_id = '.intval($user_id),
        ]);

        return $this;
    }
}
