<?php
namespace JCustomCakephp3\Validation;

use Exception;

class CustomProvider
{

    public function datetimeBR($value) {
        return true;
    }

    public function __call($method, $arguments)
    {
        if (!is_callable($method, $this)) {
            throw new Exception('Undefined respect validation method');
        }

        return call_user_func_array([$this, $method], $arguments);
    }
}