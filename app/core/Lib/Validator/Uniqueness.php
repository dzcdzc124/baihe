<?php

namespace App\Lib\Validator;

use Phalcon\Validation;
use Phalcon\Validation\Validator;
use Phalcon\Validation\Exception;
use Phalcon\Validation\Message;

class Uniqueness extends Validator
{
    /**
     * Executes the validation
     */
    public function validate(Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        $model = $this->getOption('model');
        $attribute = $this->getOption('attribute');
        $filter = $this->getOption('filter');

        if ($this->isSetOption('allowEmpty') && empty($value))
            return true;

        if (empty($model))
            throw new Exception('Model must be set');

        if (empty($attribute))
            $attribute = $field;
        
        $record = $model::findFirst([
            'conditions' => $attribute . ' = :value:',
            'bind' => [
                'value' => $value,
            ],
        ]);

        if ( ! empty($record)) {
            if (is_callable($filter) && call_user_func($filter, $record))
                return true;

            $label = $this->getOption('label');

            if (empty($label))
                $label = $validation->getLabel($field);

            $message = $this->getOption('message');
            $replacePairs = [':field' => $label];

            if (empty($message))
                $message = $validation->getDefaultMessage('Uniqueness');

            $validation->appendMessage(new Message(strtr($message, $replacePairs), $field, 'Uniqueness'));
            return false;
        }

        return true;
    }
}