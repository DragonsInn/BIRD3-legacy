<?php namespace BIRD3\Support\Model;

use Exception;
use Validator;

trait Validatable {
    private $errors;
    public function errors() {
        return $this->errors;
    }

    private $_validator=null;
    public function validator($data) {
        // Validate all deh data. o.o
        if($this->_validator == null) {
            if(!isset($this->rules)) {
                throw new Exception("No \$rules property available. Need one in order to validate!");
            }

            $this->_validator = Validatable::make($data, $this->rules);
        }
        return $this->_validator;
    }
    public function validate($data) {
        return $this->valodator($data)->passes();
    }
}
