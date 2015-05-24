<?php

/**
 * @property integer $user_id
 * @property integer $tr_id
 * @property integer $mark
 */
class Mark extends CActiveRecord
{
    /** @returns Mark */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'marks';
    }

    public function primaryKey()
    {
        return ['tr_id', 'user_id'];
    }

    public function relations()
    {
        return [
            'user' => [self::BELONGS_TO, 'User', 'user_id'],
            'translate' => [self::BELONGS_TO, 'Translate', 'tr_id'],
        ];
    }

    public function rules()
    {
        return [
            ['mark', 'in', 'range' => [-1, 0, 1]],
        ];
    }
}
