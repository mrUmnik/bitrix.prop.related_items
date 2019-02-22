<?php
namespace Zylyov\RelatedItems;

class LazyLoadList {
    private $value;
    private $storage;

    public function __construct($value, Storage\StorageInterface $storage)
    {
        $this->value = $value;
        $this->storage = $storage;
        $this->storage->addValue($value);
    }

    public function __toString()
    {
        return $this->storage->getValue($this->value);
    }
}