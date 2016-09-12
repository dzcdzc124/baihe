<?php

namespace App\Lib\Forms\Element;

use Phalcon\Tag;
use Phalcon\Forms\Element;
use Phalcon\Forms\ElementInterface;


class RadioList extends Element implements ElementInterface
{
    public function __construct($name, $options, $attributes = null)
    {
        $this->_options = (array) $options;
        parent::__construct($name, $attributes);
    }

    public function render($attributes = null)
    {
        $attributes = (array) $attributes;

        $template = '<label>{INPUT} {LABEL}</label>';
        if (isset($attributes['template'])) {
            $template = $attributes['template'];
            unset($attributes['template']);
        }

        $name = $this->getName();
        $value = $this->getValue();

        $html = '';
        $i = 1;
        foreach ($this->_options as $val => $label) {
            $attributes['id'] = $name . '_' . ($i ++);
            $attributes['name'] = $name;
            $attributes['value'] = $val;

            if ($val == $value)
                $attributes['checked'] = 'checked';
            elseif (isset($attributes['checked']))
                unset($attributes['checked']);

            $input = Tag::radioField($this->prepareAttributes($attributes, true));

            $html .= str_replace(['{LABEL}', '{INPUT}'], [$label, $input], $template);
        }

        return $html;
    }
}