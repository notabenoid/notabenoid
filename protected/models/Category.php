<?php

class Category extends CActiveRecord
{
    /** @return Category */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'catalog';
    }

    public $id, $pid, $mp = [], $title, $available = true;

    public function rules()
    {
        return [
            ['title', 'required', 'message' => 'Введите название раздела'],
            ['title', 'filter', 'filter' => 'htmlspecialchars'],
            ['available', 'boolean'],
        ];
    }

    public function relations()
    {
        return [
            'booksCount' => [self::STAT, 'Book', 'cat_id'],
//			"books" => array(self::HAS_MANY, "Book", "cat_id"),
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => '',
            'available' => 'Можно добавлять переводы',
        ];
    }

    /**
     * @param array $branch - Ветка каталога, префикс для mp (будет WHERE mp[1:$n] = $branch)
     *
     * @return Category
     */
    public function tree($branch = null)
    {
        $c = $this->getDbCriteria();
        $c->mergeWith([
            'order' => 't.mp',
        ]);
        if (is_array($branch)) {
            $n = count($branch);
            $c->addCondition("t.mp[1:{$n}] = '{".implode(',', $branch)."}'");
        }

        return $this;
    }

    public function indented_list()
    {
        $this->getDbCriteria()->mergeWith([
            'select' => ['t.*', new CDbExpression("repeat('...', array_upper(mp, 1) - 1) || title as title")],
            'order' => 't.mp',
        ]);

        return $this;
    }

    public function getMpPacked($mp = null)
    {
        if ($mp === null) {
            $mp = $this->mp;
        }
        $packed = '{'.implode(',', $mp).'}';

        return $packed;
    }

    public function packArrays()
    {
        $this->mp = '{'.implode(',', $this->mp).'}';
//		$this->path_id    = "{"  . join(",", $this->path_id) . "}";
//		$this->path_title = "{'" . join("','", $this->path_title) . "'}";
    }

    public function unpackArrays()
    {
        if (!is_array($this->mp)) {
            $this->mp = explode(',', substr($this->mp, 1, -1));
        }
//		if(!is_array($this->path_id))    $this->path_id    = array_filter(explode(",", substr($this->path_id, 1, -1)));
//		if(!is_array($this->path_title)) $this->path_title = array_filter(explode("','", substr($this->path_title, 2, -2)));
    }

    public function afterFind()
    {
        $this->unpackArrays();
    }

    public function beforeSave()
    {
        $this->packArrays();

//		if(!$this->isNewRecord) {
//			$parent = Category::model()->findByPk($this->pid);
//			if($parent) {
//				$this->path = $parent->path . "\n";
//			} else {
//				$this->path = "";
//			}
//			$this->path .= "{$this->id}\t{$this->title}";
//		}

        return true;
    }

    public function afterSave()
    {
        $this->unpackArrays();

//		if($this->isNewRecord) {
//			$this->path_id[] = $this->id;
//			$this->path_title[] = $this->title;
//
//			Yii::app()->db->createCommand("UPDATE catalog SET path_id = :path_id, path_title = :path_title WHERE id = :id")
//				->execute(array(
//					":path_id" => "{" . join(",", $this->path_id) . "}",
//					":path_title" => "{'" . join("','", $this->path_title) . "'}",
//					":id" => $this->id
//				));
//		}
    }

    public function beforeDelete()
    {
        $has_kids = Yii::app()->db->createCommand('SELECT 1 FROM catalog WHERE pid = :id LIMIT 1')->query([':id' => $this->id])->count();
        if ($has_kids) {
            $this->addError('id', 'У этого раздела есть подразделы, сначала удалите их.');

            return false;
        }

        $has_books = Yii::app()->db->createCommand('SELECT 1 FROM books WHERE cat_id = :id LIMIT 1')->query([':id' => $this->id])->count();
        if ($has_books) {
            $this->addError('id', 'В этом разделе есть переводы, перенесите их в другой раздел.');

            return false;
        }

        return true;
    }

    public function setParent($parent)
    {
        $this->pid = $parent->id;

        // Считаем максимальный ind среди будущих сестёр
        $max_mp = Yii::app()->db->createCommand('SELECT max(mp) FROM catalog WHERE pid '.($parent->id ? "= '{$parent->id}'" : 'IS NULL'))
            ->queryScalar();

        if ($max_mp == '') {
            $this->mp = $parent->mp;
            $this->mp[] = 1;
        } else {
            // unpack возвращает массив с индексами от 1!
            $this->mp = explode(',', substr($max_mp, 1, -1));
            $this->mp[count($this->mp) - 1]++;
        }

//		$this->path_id = $parent->path_id;
//		$this->path_title = $parent->path_title;
    }

    public function calcPath()
    {
        $path_mp = [];
        for ($i = 1; $i < count($this->mp); $i++) {
            $path_mp[] = '{'.implode(',', array_slice($this->mp, 0, $i)).'}';
        }
        $path = self::model()->findAllByAttributes(['mp' => $path_mp], ['order' => 'mp']);
        $this->path = '';
        foreach ($path as $c) {
            $this->path .= "{$c->id}\t{$c->title}\n";
        }
        $this->path .= "{$this->id}'\t{$this->title}";

        $this->save(false, ['path']);
    }

    public function getUrl()
    {
        if (count($this->mp) <= 1) {
            return "/catalog/{$this->id}";
        } else {
            return "/search?cat={$this->id}";
        }
    }

    public function getPathHtml()
    {
        $path_mp = [];
        for ($i = 1; $i < count($this->mp); $i++) {
            $path_mp[] = '{'.implode(',', array_slice($this->mp, 0, $i)).'}';
        }
        $path = self::model()->findAllByAttributes(['mp' => $path_mp], ['order' => 'mp']);

        $html = '';
        foreach ($path as $c) {
            $html .= "<a href='{$c->url}'>{$c->title}</a> / ";
        }
        $html .= "<a href='{$this->url}'>{$this->title}</a>";

        return $html;
    }

    public function getErrorsString()
    {
        $t = '';
        foreach ($this->getErrors() as $field => $errors) {
            $t .= implode("\n", $errors);
        }

        return $t;
    }

    public function getDump()
    {
        if (!is_array($this->mp)) {
            return "{$this->id} [".$this->mp."] '{$this->title}'";
        }

        return "{$this->id} [".implode(',', $this->mp)."] '{$this->title}'";
    }
}
