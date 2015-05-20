<?php

class UserEditor extends User
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public $name, $icq, $lj, $url, $skype, $bdate, $bdate_y, $bdate_m, $bdate_d, $country_id, $city, $bio, $new_upic, $rm_upic;

    public static $Properties = [
        1 => ['Имя',                    'name', 60, 60],
        2 => ['ICQ',                    'icq', 16, 16],
        3 => ['ЖЖ',                    'lj', 16, 16],
        4 => ['Домашняя страница',    'url', 255, 60],
        5 => ['skype',                'skype', 32, 16],
        6 => ['Дата рождения',        'bdate', 10, 16],
        7 => ['Страна',                'country_id', 'не скажу'],
        8 => ['Город',                'city', 60, 60],
        9 => ['Несколько слов о себе', 'bio', 3, 60, true],
    ];

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['name, icq, lj, url, skype, bdate_y, bdate_m, bdate_d, country_id, city, bio', 'safe'],
            ['name, icq, lj, url, skype, city', 'clean'],
            ['bio', 'safehtml'],
            ['bdate_y, bdate_m, bdate_d, country_id', 'numerical', 'integerOnly' => true],
            ['icq', 'match', 'pattern' => '/^[\d -]+$/', 'message' => 'номер icq может состоять только из цифр и дефисов'],
            ['lj', 'match', 'pattern' => '/^[a-z\d_-]+$/i', 'message' => 'введите ваш ник в ЖЖ'],
            ['url', 'url', 'defaultScheme' => 'http', 'message' => 'это не похоже на адрес сайта'],
            ['rm_upic', 'boolean'],
            ['new_upic', 'file', 'allowEmpty' => true, 'types' => 'jpg, gif, png, jpeg', 'wrongType' => 'Неверный формат файла. Пожалуйста, загружайте JPG, PNG или GIF'],
        ]);
    }

    public function clean($attr, $params)
    {
        $this->$attr = trim(htmlspecialchars(strip_tags($this->$attr, ENT_QUOTES | ENT_HTML5)));
    }

    public function safehtml($attr, $params)
    {
        $p = new CHtmlPurifier();
        $p->options = Yii::app()->params['HTMLPurifierOptions'];
        $this->$attr = trim($p->purify($this->$attr));
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'icq' => 'ICQ',
            'lj' => 'ЖЖ',
            'url' => 'Домашняя страница',
            'skype' => 'Skype',
            'bdate' => 'День рождения',
            'country_id' => 'Страна',
            'city' => 'Город',
            'bio' => 'Несколько слов о себе',
            'new_upic' => 'Аватар',
            'rm_upic' => 'удалить',
        ];
    }

    protected function afterFind()
    {
        // загружаем userinfo
        $r = Yii::app()->db->createCommand('SELECT prop_id, value FROM userinfo WHERE user_id = :user_id')->query([':user_id' => $this->id]);
        foreach ($r as $row) {
            $attr = self::$Properties[$row['prop_id']][1];
            if ($attr == 'bdate') {
                list($this->bdate_y, $this->bdate_m, $this->bdate_d) = sscanf($row['value'], '%d-%d-%d');
                foreach (['bdate_y', 'bdate_m', 'bdate_d'] as $k) {
                    if ($this->$k == 0) {
                        $this->$k = '';
                    }
                }
            }
            $this->$attr = $row['value'];
        }

        parent::afterFind();
    }

    protected function afterValidate()
    {
        // Формируем bdate
        $this->bdate = sprintf('%04d-%02d-%02d', $this->bdate_y, $this->bdate_m, $this->bdate_d);

        parent::afterValidate();
    }

    protected function afterSave()
    {
        // сохраняем userinfo
        Yii::app()->db->createCommand('DELETE FROM userinfo WHERE user_id = :user_id')->execute([':user_id' => $this->id]);
        foreach (self::$Properties as $prop_id => $P) {
            $attr = $P[1];
            Yii::app()->db->createCommand()->insert('userinfo', ['user_id' => $this->id, 'prop_id' => $prop_id, 'value' => $this->$attr]);
        }
    }
}
