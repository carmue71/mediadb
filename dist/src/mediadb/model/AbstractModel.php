<?php

/* AbstractModel.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Base class for all models used in mediadb
 */

namespace mediadb\model;
use \ArrayAccess;

class AbstractModel implements ArrayAccess
{

    public function __construct()
    {
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
    
    public function offsetGet($offset)
    {
        return $this->$offset;
    }
    
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }
    
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
}
