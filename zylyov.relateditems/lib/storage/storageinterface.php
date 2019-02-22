<?php

namespace Zylyov\RelatedItems\Storage;

interface StorageInterface
{
    /**
     * Метод должен возвращать синглтон объекта хранилища
     */
    public static function getInstance();

    /**
     * @param array|string $value
     *
     * Метод добавляет идентификатор или массив идентификаторов в хранилище
     */
    public function addValue($value);

    /**
     * @param array|string $value
     *
     * Метод возвращает отформатированное значение из хранилища по идентификатору или массиву идентификаторов
     */
    public function getValue($value);
}