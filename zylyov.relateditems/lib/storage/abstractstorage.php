<?php

namespace Zylyov\RelatedItems\Storage;

abstract class AbstractStorage implements StorageInterface
{
    protected static $instance = [];
    protected $loaded = false;
    protected $loadedValues = [];
    protected $values = [];

    protected function __construct()
    {
    }

    /**
     * @return AbstractStorage
     */
    public static function getInstance()
    {
        if (!self::$instance[static::class]) {
            self::$instance[static::class] = new static();
        }
        return self::$instance[static::class];
    }

    /**
     * @param array|string $value
     */
    public function addValue($value)
    {
        if (!strlen($value)) {
            return;
        }
        if (is_array($value)) {
            $this->values = array_merge($this->values, $value);
        } else {
            $this->values[] = $value;
        }
    }

    /**
     * @param array|string $value
     * @return string
     */
    public function getValue($value)
    {
        if (!is_array($value)) {
            $value = strlen($value) ? [$value] : [];
        }
        if (empty($value)) {
            return "";
        }

        $this->checkLoaded($value);

        $_values = [];
        foreach ($value as $v) {
            $_values[] = $this->loadedValues[$v];
        }
        return $this->getFormattedValue($_values);
    }

    /**
     * @param $values
     *
     * Проверяет загружены ли данные по идентификаторам, хранящимся в $values и при необходимости догружает
     */
    protected function checkLoaded($values)
    {
        if (!$this->loaded) {
            if (!empty($this->values)) {
                $this->loadedValues = $this->load($this->values);
            }
        } else {
            // догурзка значений которые не были загружены
            $valuesToLoad = [];
            foreach ($values as $value) {
                if (!in_array($value, $this->values)) {
                    $valuesToLoad[] = $value;
                }
            }
            if (!empty($valuesToLoad)) {
                $this->values = array_merge($this->values, $valuesToLoad);
                $this->loadedValues = array_merge($this->loadedValues, $this->load($valuesToLoad));
            }
        }
        $this->loaded = true;
    }

    /**
     * @param array $valueIds
     * @return array
     *
     * Метод возвращает данные, загруженные из соответствующего хранилища по массиву идентификаторов $valueIds
     */
    abstract protected function load(array $valueIds);

    /**
     * @param array $valueIds
     * @return string
     *
     * Метод возвращает отформатированное тектовое значение по массиву идентификаторов $valueIds
     */
    abstract protected function getFormattedValue(array $valueIds);
}