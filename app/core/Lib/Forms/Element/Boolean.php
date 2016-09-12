<?php

namespace App\Lib\Forms\Element;

use Phalcon\Tag;
use Phalcon\Forms\Element\Check;

class Boolean extends Check
{
    public function render($attributes = null)
    {
        $attributes = (array) $attributes;

        // add hidden field.
        $hidden = [
            'value' => 0,
            'name' => $this->getName(),
            'id' => $this->getName() . '_hidden',
        ];
        $html = Tag::hiddenField($this->prepareAttributes($hidden, true));

        $attributes['value'] = 1;
        $html .= Tag::checkField($this->prepareAttributes($attributes, true));

        return $html;
    }
}