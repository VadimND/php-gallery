# Компонент вывода карточки пользователя

## Описание
Компонент выводит на страницу фото, имя и почту зарегитрированного пользователя.

## Структура компонента
user.card/  

    ├── .parameters.php  
    ├── .description.php  
    ├── class.php 
    └── lang/  
        └── ru/  
            └── message.php             
    └── templates/  
        └── .default/  
            └── template.php  
            └── style.css
            └── script.js

## Использование
```
<?$APPLICATION->IncludeComponent(
    "my_component:user.card",
    "",
    Array(
        "SHOW_EMAIL" => "Y",
        "USER_ID" => "1"
    )
);?>
```