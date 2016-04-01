<?php

class Userinfo extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'userinfo';
    }

    public function primaryKey()
    {
        return ['user_id', 'prop_id'];
    }

    public $user_id, $prop_id, $value;

    public function user($id)
    {
        $this->dbCriteria->mergeWith([
            'condition' => 'user_id = '.intval($id),
            'select' => 'prop_id, value',
            'order' => 'prop_id',
        ]);

        return $this;
    }

    public static $Properties = [
        1 => ['Имя',                    'line', 60, 60],
        2 => ['ICQ',                    'int', 16, 16],
        3 => ['ЖЖ',                    'line', 16, 16],
        4 => ['Домашняя страница',    'line', 255, 60],
        5 => ['skype',                'line', 32, 16],
        6 => ['День рождения',        'date', 10, 16],
        7 => ['Страна',                'select', 'не скажу'],
        8 => ['Город',                'line', 60, 60],
        9 => ['Несколько слов о себе', 'text', 3, 60, true],
    ];

    public function getLabel()
    {
        return self::$Properties[$this->prop_id][0];
    }

    public function getType()
    {
        return self::$Properties[$this->prop_id][1];
    }

    public function getValueFormatted()
    {
        if ($this->prop_id == 3) {
            return "<a href='http://".$this->value.".livejournal.com/' target='_blank' rel='nofollow'>{$this->value}</a>";
        }

        if ($this->prop_id == 4) {
            $url = 'http://'.str_replace('http://', '', $this->value);

            return '<a href="'.htmlentities($url).'" target="_blank" rel="nofollow">'.$this->value.'</a>';
        }

        if ($this->prop_id == 6) {
            list($y, $m, $d) = sscanf($this->value, '%d-%d-%d');

            if ($d == 0 and $m == 0 and $y == 0) {
                return '';
            }
            if ($d == 0 and $m == 0) {
                return "в {$y} г.";
            }
            if ($d == 0 and $y == 0) {
                return 'в '.Yii::app()->params['month_in'][$m];
            }
            if ($m == 0 and $y == 0) {
                return "{$d}-го";
            }
            if ($d == 0) {
                return 'в '.Yii::app()->params['month_in'][$m]." {$y}-го";
            }
            if ($m == 0) {
                return "{$d} мартобря {$y}";
            }

            $link = "http://ru.wikipedia.org/wiki/{$d}_".urlencode(Yii::app()->params['month_acc'][$m]);
            $ret = "<a href='$link' rel='nofollow'>";
            if ($y == 0) {
                $ret .= "{$d} ".Yii::app()->params['month_acc'][$m];
            } else {
                $ret .= sprintf('%d %s %d г.', $d, Yii::app()->params['month_acc'][intval($m)], $y);
            }
            $ret .= '</a>';

            return $ret;
        }

        if ($this->prop_id == 7) {
            if ($this->value == 0 || $this->value == 'не скажу') {
                return '';
            }

            return Yii::app()->params['countries'][$this->value];
        }

        if ($this->prop_id == 9) {
            return nl2br($this->value);
        }

        return $this->value;
    }
}
