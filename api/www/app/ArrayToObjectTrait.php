<?php

namespace App;

/**
 * Overrides calls to methods beginning with "get" and returns its
 * corresponding values inside the data array property
 */
trait ArrayToObjectTrait
{
    /**
     * @var array Array data which will be returned with get methods
     */
    private $data;

    /**
     * Overrides the getters to match the array keys, returning their values
     * Adapted from https://stackoverflow.com/a/46714520
     *
     * @param string $property
     * @param mixed $args
     * @return mixed
     */
    public function __call($property, $args)
    {
        $property = lcfirst(str_replace('get', '', $property));
        $property = strtolower(preg_replace('/([a-z])([A-Z])/', '${1}_${2}', $property));

        if (array_key_exists($property, $this->data)) {
            if (is_array($this->data[$property])) {
                return new self($this->data[$property]);
            }

            return $this->data[$property];
        }

        return null;
    }
}