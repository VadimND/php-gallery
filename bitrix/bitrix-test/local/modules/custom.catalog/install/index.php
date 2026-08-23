<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;

class custom_catalog extends CModule
{
    public $MODULE_ID = 'custom.catalog';
    public $MODULE_VERSION = '1.0.0';
    public $MODULE_VERSION_DATE = '2024-01-01';
    public $MODULE_NAME = 'Custom Catalog';
    public $MODULE_DESCRIPTION = 'Каталог: связанные товары и скидки';

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler(
            'iblock',
            'OnBeforeIBlockElementUpdate',
            $this->MODULE_ID,
            'Custom\\Catalog\\EventHandlers',
            'onBeforeElementUpdate'
        );
        $eventManager->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Custom\\Catalog\\EventHandlers',
            'onAfterElementUpdate'
        );
    }

    public function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnBeforeIBlockElementUpdate',
            $this->MODULE_ID,
            'Custom\\Catalog\\EventHandlers',
            'onBeforeElementUpdate'
        );
        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Custom\\Catalog\\EventHandlers',
            'onAfterElementUpdate'
        );
    }
}
