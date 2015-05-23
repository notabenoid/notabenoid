<?php

class ImportOptionsSubs extends CFormModel
{
    public $src, $format, $encoding;

    public function rules()
    {
        return [
            // login and pass are required
            ['src', 'file', 'message' => 'Пожалуйста, выберите файл.', 'maxSize' => 1024 * 1024, 'minSize' => 1,
                'tooLarge' => 'Файл слишком большой', 'tooSmall' => 'Файл подозрительно маленький',
            ],
            ['format', 'required'],
            ['encoding', 'required'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'src' => 'Файл с субтитрами',
            'format' => 'Формат',
            'encoding' => 'Кодировка',
        ];
    }
}
