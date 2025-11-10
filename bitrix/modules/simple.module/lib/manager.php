<?php

namespace Your\Module;

use Bitrix\Main\Application;

class Manager
{
    public static function doSomething()
    {
        // Логика модуля
        return "Модуль работает!";
    }
    
    public static function getSomeData()
    {
        $connection = Application::getConnection();
        $result = $connection->query("SELECT * FROM b_your_module_example WHERE ACTIVE='Y'");
        
        return $result->fetchAll();
    }
}