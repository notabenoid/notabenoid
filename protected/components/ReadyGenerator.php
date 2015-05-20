<?php

class ReadyGenerator
{
    public static function factory($options, $chap, $orig)
    {
        $classname = 'ReadyGenerator_'.$options->format;

        return new $classname($options, $chap, $orig);
    }
}

abstract class ReadyGenerator_base
{
    /** @var GenOptions */
    public $options;
    /** @var Chapter */
    public $chap;
    /** @var Orig[] */
    public $orig;

    protected $translators = array();
    protected $eol;

    public function __construct($options, $chap, $orig)
    {
        $this->options = $options;
        $this->chap = $chap;
        $this->orig = $orig;

        $this->eol = $options->eol;
    }

    public function generate($return = false, $skip_credits = false)
    {
        if ($return) {
            ob_start();
        }

        $this->header();

        $cnt = 0;
        foreach ($this->orig as $o) {
            // Если нет версий перевода или выбран пропуск отрицательных вариантов и у лучшего из них отрицательный рейтинг
            if (count($o->trs) == 0 || ($this->options->skip_neg && $o->trs[0]->rating < 0)) {
                if ($this->options->untr == 's') {
                    continue;
                } else {
                    $tr_body = $o->body;
                }
            } else {
                $tr_body = $o->trs[0]->body;
                $this->translators[$o->trs[0]->user->login]++;
            }

            $this->verse($o->stdtime('t1'), $o->stdtime('t2'), $tr_body, $o->ord);
            $cnt++;
        }

        if ($cnt == 0) {
            $o = new Orig();
            $o->setAttributes(array('t1' => '00:00:00.000', 't2' => '00:00:00.000', 'ord' => 0), false);
        }

        if ($this->chap->status != Chapter::STATUS_NONE && $this->chap->status != Chapter::STATUS_READY) {
            $o->t1 = Orig::ms2std($o->mstime('t2') + 10);
            $o->t2 = Orig::ms2std($o->mstime('t2') + 1500);
            $this->verse($o->t1, $o->t2, "Внимание! Этот перевод, возможно, ещё не готов.{$this->eol}Его статус: ".Yii::app()->params['translation_statuses'][$this->chap->status]);
        }

        if (!$skip_credits) {
            // Переведено пользователями
            $this->verse(Orig::ms2std($o->mstime('t2') + 10), Orig::ms2std($o->mstime('t2') + 2500), "Переведено на Нотабеноиде{$this->eol}http://".Yii::app()->params['domain'].$this->chap->url);

            // Первые 16 переводчиков
            arsort($this->translators);
            if (($nt = count($this->translators)) > 16) {
                $this->translators = array_slice($this->translators, 0, 16);
            }
            $this->translators = array_keys($this->translators);

            $t = $o->mstime('t2') + 2510;
            for ($i = 0; $i < ceil(count($this->translators) / 4); $i++) {
                $t1 = Orig::ms2std($t);
                $t2 = Orig::ms2std($t + 990);

                $txt = '';
                if ($i == 0) {
                    $txt .= 'Переводчики: ';
                }
                for ($j = 0; $j < 4; $j++) {
                    if (!isset($this->translators[$i * 4 + $j])) {
                        break;
                    }
                    if ($j) {
                        $txt .= ', ';
                    }
                    $txt .= $this->translators[$i * 4 + $j];
                }
                if ($i == 3 && $nt > 16) {
                    $txt .= ' и ещё '.Yii::t('app', '{n} человек|{n} человека|{n} человек', $nt - 16);
                }

                $this->verse($t1, $t2, $txt);

                $t += 1000;
            }
        }

        $this->footer();

        if ($return) {
            return ob_get_clean();
        } else {
            return true;
        }
    }

    public function generateOrig($return = false)
    {
        if ($return) {
            ob_start();
        }

        foreach ($this->orig as $o) {
            $this->verse($o->stdtime('t1'), $o->stdtime('t2'), $o->body);
        }

        if ($return) {
            return ob_get_clean();
        } else {
            return true;
        }
    }

    public function header()
    {
    }

    /** @todo: принимать в параметре ссылку на экземпляр Orig, а не частные его свойства */
    abstract public function verse($t1, $t2, $body, $ord = null);

    public function footer()
    {
    }
}

class ReadyGenerator_s extends ReadyGenerator_base
{
    private $cnt = 0;
    public function verse($t1, $t2, $body, $ord = null)
    {
        if ($ord) {
            $this->cnt = $ord;
        } else {
            $this->cnt++;
        }

        $t1 = str_replace('.', ',', $t1);
        $t2 = str_replace('.', ',', $t2);

        echo $this->cnt.$this->eol;
        echo "{$t1} --> {$t2}{$this->eol}";
        echo trim($body);
        echo $this->eol.$this->eol;
    }
}

class ReadyGenerator_m extends ReadyGenerator_base
{
    public function header()
    {
        echo "<SAMI>{$this->eol}<HEAD>";
        echo '<TITLE>'.htmlspecialchars($this->chap->book->fullTitle).' '.htmlspecialchars($this->chap->title).' generated by '.Yii::app()->params['domain'].'</TITLE>';
        echo "{$this->eol}</HEAD>{$this->eol}<BODY>{$this->eol}<TABLE>{$this->eol}";
    }

    public function verse($t1, $t2, $body, $ord = null)
    {
        echo '<SYNC Start='.Orig::std2ms($t1).">{$this->eol}";
        echo '<P>'.trim($body)."</P>{$this->eol}{$this->eol}";
    }

    public function footer()
    {
        echo "{$this->eol}</TABLE>{$this->eol}</BODY>{$this->eol}</SAMI>{$this->eol}";
    }
}

class ReadyGenerator_b extends ReadyGenerator_base
{
    public function header()
    {
        echo "[INFORMATION] {$this->chap->book->fullTitle} {$this->chap->title}{$this->eol}";
        echo "[TITLE] {$this->chap->book->fullTitle} {$this->chap->title}{$this->eol}";
        echo "[AUTHOR] {$this->chap->book->owner->login}{$this->eol}";
        echo '[SOURCE] '.Yii::app()->name."{$this->eol}";
        echo "[PRG] 1{$this->eol}";
        echo "[FILEPATH] {$this->eol}";
        echo "[DELAY] 0{$this->eol}";
        echo "[CD TRACK] 1{$this->eol}";
        echo '[COMMENT] Переведено на '.Yii::app()->name."{$this->eol}";
        echo "[END INFORMATION]{$this->eol}";
        echo "[SUBTITLE]{$this->eol}";
        echo "[COLF]&HFFFFFF,[STYLE]no,[SIZE]18,[FONT]Arial{$this->eol}";
    }

    public function verse($t1, $t2, $body, $ord = null)
    {
        $t1 = substr($t1, 0, -1);
        $t2 = substr($t2, 0, -1);
        echo "{$t1},{$t2}{$this->eol}";
        echo trim($body).$this->eol.$this->eol;
    }
}

class ReadyGenerator_h extends ReadyGenerator_base
{
    public function verse($t1, $t2, $body, $ord = null)
    {
        echo '<p>'.nl2br(htmlspecialchars($body))."</p>\n";
    }
}

class ReadyGenerator_t extends ReadyGenerator_base
{
    public function verse($t1, $t2, $body, $ord = null)
    {
        echo "{$body}{$this->eol}{$this->eol}";
    }
}
