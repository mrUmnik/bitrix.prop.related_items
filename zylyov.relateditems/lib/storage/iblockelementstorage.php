<?php

namespace Zylyov\RelatedItems\Storage;

class IblockElementStorage extends AbstractStorage
{
    protected function load(array $valueIds)
    {
        $result = [];
        if (empty($valueIds)) {
            return $result;
        }
        $rs = \CIBlockElement::GetList(
            array(),
            array("ID" => $valueIds),
            false,
            false,
            array("ID", "NAME", "DETAIL_PAGE_URL")
        );

        while ($ar = $rs->GetNext()) {
            $result[$ar["ID"]] = [
                "NAME" => $ar["NAME"],
                "DETAIL_PAGE_URL" => $ar["DETAIL_PAGE_URL"],

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
            $result[] = strlen($arValue["DETAIL_PAGE_URL"]) ?
                "<a href=\"" . $arValue["DETAIL_PAGE_URL"] . "\">" . $arValue["NAME"] . "</a>" :
                $arValue["NAME"];
        }

        return implode(", ", $result);
    }
}