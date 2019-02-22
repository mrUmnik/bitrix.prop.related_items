<?php

namespace Zylyov\RelatedItems;

use Bitrix\Main\Localization\Loc;

class UserField
{
    public static function GetUserTypeDescription()
    {
        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "RelatedItems",
            "DESCRIPTION" => Loc::getMessage("ZRI.RELATED_ITEMS"),
            "GetPublicViewHTML" => array(__CLASS__, "GetPublicViewHTML"),
            //"GetPropertyFieldHtml" => array(__CLASS__,"GetPropertyFieldHtml"),
            "GetPublicEditHTML" => array(__CLASS__, "GetPublicEditHTML"),
            "GetSettingsHTML" => array(__CLASS__, "GetSettingsHTML"),
            "PrepareSettings" => array(__CLASS__, "PrepareSettings"),
        );
    }


    public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        if (is_array($value) && isset($value["VALUE"])) {
            $value = $value["VALUE"];
        }
        if (strlen($value)) {
            $errors = [];
            if (!array_key_exists("STORAGE_CLASS", $arProperty["USER_TYPE_SETTINGS"]) ||
                !strlen($arProperty["USER_TYPE_SETTINGS"]["STORAGE_CLASS"])
            ) {
                $errors[] = Loc::getMessage("ZRI.STORAGE_CLASS_EMPTY");
            } elseif (!class_exists(($arProperty["USER_TYPE_SETTINGS"]["STORAGE_CLASS"]))) {
                $errors[] = Loc::getMessage("ZRI.STORAGE_CLASS_NOT_FOUND");
            } else {
                $testInstance = $arProperty["USER_TYPE_SETTINGS"]["STORAGE_CLASS"]::getInstance();
                if (!$testInstance instanceof Storage\StorageInterface) {
                    $errors[] = Loc::getMessage("ZRI.STORAGE_CLASS_NOT_IMPLEMENTED");
                }
            }

            if (!empty($errors)) {
                throw new Exception(implode(", ", $errors));
            }
            $className = $arProperty["USER_TYPE_SETTINGS"]["STORAGE_CLASS"];
            $value = new LazyLoadList($value, $className::getInstance());
        }
        return $value;
    }

    public static function GetPublicEditHTML($arProperty, $value, $strHTMLControlName)
    {
        $result = "<input type=\"text\" name=\"" . $strHTMLControlName["VALUE"] . "\" value=\"" . $value["VALUE"] . "\" />";
        return $result;
    }

    public static function PrepareSettings($arProperty)
    {
        return [
            "USER_TYPE_SETTINGS" => $arProperty["USER_TYPE_SETTINGS"]
        ];
    }

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
    {
        return "<tr>
            <td>" . Loc::getMessage("ZRI.STORAGE_CLASS") . ":</td>
            <td><input type=\"text\" size=\"50\" value=\"" . $arProperty["USER_TYPE_SETTINGS"]["STORAGE_CLASS"] . "\" name=\"" . $strHTMLControlName["NAME"] . "[STORAGE_CLASS]\"></td>
        </tr>";
    }
}