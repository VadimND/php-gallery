<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class your_module extends CModule
{
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $MODULE_GROUP_RIGHTS;

    public function __construct()
    {
        $this->MODULE_ID = 'your.module';
        $this->MODULE_NAME = Loc::getMessage('YOUR_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('YOUR_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('YOUR_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'https://your-site.ru';
        
        $arModuleVersion = [];
        include __DIR__ . '/version.php';
        
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    }

    // Установка модуля
    public function DoInstall()
    {
        global $APPLICATION;
        
        // Проверяем системные требования
        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            
            // Создаем таблицы в БД
            $this->installDB();
            
            // Устанавливаем события
            $this->installEvents();
            
            // Устанавливаем файлы
            $this->installFiles();
            
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('YOUR_MODULE_INSTALL_TITLE'),
                __DIR__ . '/step1.php'
            );
        } else {
            $APPLICATION->ThrowException(Loc::getMessage('YOUR_MODULE_INSTALL_ERROR_D7'));
        }
    }

    // Удаление модуля
    public function DoUninstall()
    {
        global $APPLICATION;
        
        // Спрашиваем удалять ли таблицы
        $request = Application::getInstance()->getContext()->getRequest();
        
        if ($request['step'] < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('YOUR_MODULE_UNINSTALL_TITLE'),
                __DIR__ . '/unstep1.php'
            );
        } elseif ($request['step'] == 2) {
            // Удаляем таблицы если пользователь согласился
            if ($request['savedata'] != 'Y') {
                $this->uninstallDB();
            }
            
            // Удаляем события
            $this->uninstallEvents();
            
            // Удаляем файлы
            $this->uninstallFiles();
            
            // Удаляем модуль из регистра
            ModuleManager::unRegisterModule($this->MODULE_ID);
            
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('YOUR_MODULE_UNINSTALL_TITLE'),
                __DIR__ . '/unstep2.php'
            );
        }
    }

    // Установка БД
    public function installDB()
    {
        $connection = Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        
        // Выполняем SQL из файла
        $this->runSQLFile(__DIR__ . '/db/mysql/install.sql');
        
        return true;
    }

    // Удаление БД
    public function uninstallDB()
    {
        $connection = Application::getConnection();
        
        // Удаляем таблицы
        $connection->queryExecute("DROP TABLE IF EXISTS b_your_module_example");
        
        // Удаляем настройки модуля
        Option::delete($this->MODULE_ID);
        
        return true;
    }

    // Установка событий
    public function installEvents()
    {
        $eventManager = EventManager::getInstance();
        
        // Пример: добавляем обработчик на событие
        $eventManager->registerEventHandlerCompatible(
            'main',
            'OnPageStart',
            $this->MODULE_ID,
            'Your\Module\EventHandlers',
            'onPageStart'
        );
        
        return true;
    }

    // Удаление событий
    public function uninstallEvents()
    {
        $eventManager = EventManager::getInstance();
        
        // Удаляем все обработчики нашего модуля
        $eventManager->unRegisterEventHandler('main', 'OnPageStart', $this->MODULE_ID);
        
        return true;
    }

    // Установка файлов
    public function installFiles()
    {
        // Копируем административные файлы
        CopyDirFiles(
            __DIR__ . '/admin',
            Application::getDocumentRoot() . '/bitrix/admin'
        );
        
        // Копируем компоненты
        CopyDirFiles(
            __DIR__ . '/components',
            Application::getDocumentRoot() . '/local/components',
            true, true
        );
        
        return true;
    }

    // Удаление файлов
    public function uninstallFiles()
    {
        // Удаляем административные файлы
        Directory::deleteDirectory(Application::getDocumentRoot() . '/bitrix/admin/your_module');
        
        // Удаляем компоненты
        Directory::deleteDirectory(Application::getDocumentRoot() . '/local/components/your');
        
        return true;
    }

    // Проверка версии Битрикс
    public function isVersionD7()
    {
        return CheckVersion(Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    // Выполнение SQL файла
    private function runSQLFile($filePath)
    {
        if (!file_exists($filePath)) {
            return;
        }
        
        $connection = Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        
        $fileContent = file_get_contents($filePath);
        $queries = preg_split('/;\s*$/m', $fileContent);
        
        foreach ($queries as $query) {
            if (trim($query)) {
                $connection->queryExecute($query);
            }
        }
    }
}