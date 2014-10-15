<?php namespace Amitav\MasterModel;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\Validator;

class MasterModel extends Eloquent {

    /**
     * This is the error message variable.
     */
    protected $errors;

    /**
     * Validation rules.
     */
    protected static $rules = array();

    /**
     * Custom error messages.
     */
    protected static $messages = array();

    /**
     * Validator instance
     */
    protected $validator;

    public function __construct(array $attributes = array(), Validator $validator = null)
    {
        parent::__construct($attributes);

        $this->validator = $validator ?: \App::make('validator');
    }

    /**
     * Listen to the event.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate()
    {
        $validator = $this->validator->make($this->attributes, static::$rules, static::$messages);

        if ($validator->passes())
            return true;

        $this->setErrors($validator->messages());

        return false;
    }

    /**
     * Set error message bag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }
}