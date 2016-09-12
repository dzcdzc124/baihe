<?php

namespace App\Lib\Forms;

use Phalcon\Forms\Form as PhForm;

class Form extends PhForm
{
    public function implodeMessages($glue = "，")
    {
        $messages = (array) $this->errorMessages();
        return implode($glue, $messages);
    }

    public function errorMessages()
    {
        $messages = [];
        foreach ($this->getMessages() as $message)
            $messages[] = strval($message);

        return $messages ? $messages : null;
    }
}