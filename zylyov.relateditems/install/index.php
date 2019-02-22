<?php

use Bitrix\Main\ModuleManager;

class zylyov_relateditems extends CModule
{
    public $MODULE_ID = "zylyov.relateditems";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_PATH;

    public function __construct()
    {
        $this->MODULE_NAME = "Тип свойств инфоблока \"Связанные сущности\"";
        $this->MODULE_DESCRIPTION = "";
        $this->MODULE_PATH = $this->getModulePath();

        $arModuleVersion = array();
        include $this->MODULE_PATH . "/install/version.php";

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    }

    protected function getModulePath()
    {
        $modulePath = explode("/", __FILE__);
        $modulePath = array_slice($modulePath, 0, array_search($this->MODULE_ID, $modulePath) + 1);

        return join("/", $modulePath);
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installEvents();
    }

    public function doUninstall()
    {
        $this->unInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installEvents()
    {
        RegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, "\\Zylyov\\RelatedItems\\UserField", "GetUserTypeDescription");
        return true;
    }

    public function unInstallEvents()
    {
        UnRegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", $this->MODULE_ID, "\\Zylyov\\RelatedItems\\UserField", "GetUserTypeDescription");
        return true;
    }
}
