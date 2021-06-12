<?php

namespace App\Base;


use Illuminate\Database\Eloquent\Model;


class BaseModel extends Model
{

    /**
     * Holds all the extra appended attributes that should be added to the model
     *
     * @var array
     */
    static private $globallyAppended;

    /**
     * Holds all extra attributes that should be hidden from the model
     *
     * @var array
     */
    static private $globallyHide;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $table = $this->getTable();

        if (isset(self::$globallyHide[$table])) {
            $this->hidden = array_merge($this->hidden, self::$globallyHide[$table]);
        }

        if (isset(self::$globallyAppended[$table])) {
            $this->appends = array_merge($this->appends, self::$globallyAppended[$table]);
        }
    }

    /**
     * Append attributes globally
     *
     * @param array $attributes
     */
    public static function addAppendAttributes(array $attributes)
    {
        $table = self::getInstance()->getTable();
        self::$globallyAppended[$table] = $attributes;
    }

    /**
     * Add hidden attributes globally
     *
     * @param array $attributes
     */
    public static function addHiddenAttributes(array $attributes)
    {
        $table = self::getInstance()->getTable();
        self::$globallyHide[$table] = $attributes;
    }

    /**
     * Creates and return an instance of the Eloquent model.
     *
     * @return Model
     */
    public static function getInstance()
    {
        $className = get_called_class();
        return new $className ();
    }
}