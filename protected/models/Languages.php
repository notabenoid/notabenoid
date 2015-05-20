<?php

/**
 * This is the model class for table "languages".
 *
 * The followings are the available columns in table 'languages':
 *
 * @property integer $id
 * @property integer $typ
 * @property string $title
 * @property string $title_r
 */
class Languages extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     *
     * @return Languages the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'languages';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['typ', 'required'],
            ['typ', 'numerical', 'integerOnly' => true],
            ['title, title_r', 'length', 'max' => 100],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, typ, title, title_r', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typ' => 'Typ',
            'title' => 'Title',
            'title_r' => 'Title R',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('typ', $this->typ);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('title_r', $this->title_r, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
