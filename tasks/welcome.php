<?php
/*
 * Необходимо исправить конструкцию таким образом, чтобы выводилось приветствие используя подстроки, которые определены в классе Second;
 */
namespace Company;

class First {

    public const welcome = 'Добро пожаловать';
    public const system = 'в систему';

    public static function getWelcome($name)
    {
        return self::welcome . ' ' . self::system . ($name ? ', ' . $name : '') . '!';
    }

}

class Second extends First {

    public const welcome = 'Приветствуем';
    public const system = 'в приложении';

}

echo Second::getWelcome('Вениамин');

// Решений несколько, все они связаны с особенностями синтаксиса PHP в части использования констант

// Решение 1 - с использованием модификатора доступа static
// Это основное решение, которое ближе всего соответсвует условиям задачи

class First {

    public const welcome = 'Добро пожаловать';
    public const system = 'в систему';

    public static function getWelcome($name) : string
    {
        return static::welcome . ' ' .  static::system . ($name ? ', ' . $name : '') . '!';
    }

}

class Second extends First {

    public const welcome = 'Приветствуем';
    public const system = 'в приложении';

}

echo Second::getWelcome('Вениамин');

// Вывод: Приветствуем в приложении, Вениамин!

// Решение 2 - с использованием get_called_class()
class First {

    public const welcome = 'Добро пожаловать';
    public const system = 'в систему';

    public static function getWelcome($name) : string
    {
        $a = $b = get_called_class();

        return $a::welcome . ' ' . $b::system . ($name ? ', ' . $name : '') . '!';
    }

}

class Second extends First {

    public const welcome = 'Приветствуем';
    public const system = 'в приложении';

}

echo Second::getWelcome('Вениамин');

// Вывод: Приветствуем в приложении, Вениамин!

// Решение 3 - с использованием ключевого слова $this
class First {

    public const welcome = 'Добро пожаловать';
    public const system = 'в систему';

    public function getWelcome($name) : string
    {
        return $this::welcome . ' ' .  $this::system . ($name ? ', ' . $name : '') . '!';
    }

}

class Second extends First {
    public const welcome = 'Приветствуем';
    public const system = 'в приложении';
}

$obj = new Second();

echo $obj->getWelcome('Вениамин');

// Вывод: Приветствуем в приложении, Вениамин!