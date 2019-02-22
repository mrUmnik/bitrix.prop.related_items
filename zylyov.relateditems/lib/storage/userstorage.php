<?php

namespace Zylyov\RelatedItems\Storage;

use Bitrix\Main\Text\Converter;
use Bitrix\Main\UserTable;

class UserStorage extends AbstractStorage
{
    protected function load(array $valueIds)
    {
        $result = [];
        if (empty($valueIds)) {
            return $result;
        }
        $rs = UserTable::getList([
            "filter" => ["ID" => $valueIds],
            "select" => ["ID", "NAME", "LAST_NAME", "SECOND_NAME"]
        ]);

        while ($ar = $rs->fetch(Converter::getHtmlConverter())) {
            $result[$ar["ID"]] = [
                "NAME" => $ar["NAME"],
                "LAST_NAME" => $ar["LAST_NAME"],
                "SECOND_NAME" => $ar["SECOND_NAME"],

            ];
        }
        return $result;
    }

    protected function getFormattedValue(array $values)
    {
        if (empty($values)) {
            return "";
        }
        $result = [];
        foreach ($values as $arValue) {
            $result[] = $arValue["LAST_NAME"] . " " . $arValue["NAME"] . " " . $arValue["SECOND_NAME"];
        }

        return implode(", ", $result);
    }
}