<?php namespace BIRD3\Foundation\Traits;

/**
 * Describe the state of an object.
 *
 * This is used to describe things like visibility of content, or the content rating.
 * It could also be used for other states, such as deleted, read, open or closed.
 */
trait Stateability {

    /**
     * The state's DB field/colmn name.
     * @var String
     */
    private $__state_fieldName;

    /**
     * The state's default DB field/column name.
     * @var String
     */
    private $__state_fieldDefault;

    /**
     * The state's default value for the DB column.
     * @var mixed
     */
    private $__state_defaultState;

    /**
     * Bootstrap this state.
     *
     * @param  String $fieldName    Name of the field that should be used to
     *                              configure the DB column.
     * @param  String $fieldDefault The default value for the configuration field.
     * @param  mixed $defaultState  The default state that should be applied, if empty.
     */
    private function __initState($fieldName, $fieldDefault, $defaultState) {
        $this->__state_fieldName = $fieldName;
        $this->__state_fieldDefault = $fieldDefault;
        $this->__state_defaultState = $defaultState;

        if(!isset($this->{$fieldName})) {
            $this->{$fieldName} = $fieldDefault;
        }

        $this->creating(function($model) use($fieldName, $defaultState) {
            $field = $this->{$fieldName};
            if(empty($this->{$field})) {
                $this->{$field} = $defaultState;
            }
        });
    }

    /**
     * Revert a state to it's original/default.
     */
    private function __revertState() {
        $fieldName = $this->__state_fieldName;
        $field = $this->{$fieldName};
        $defaultState = $this->__state_defaultState;
        $this->{$field} = $defaultState;
    }

}
