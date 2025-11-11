# Компонент расширенного поиска

## Описание
Компонент для расширенного поиска по темам, статьям и персоналиям в инфоблоках Битрикс. Поддерживает поиск по названиям, текстам статей и описаниям галерей.

## Структура компонента
advanced.search/  

    ├── .parameters.php  
    ├── .description.php  
    ├── component.php 
    └── templates/  
        └── .default/  
            └── template.php  
            └── style.css   

## Использование
```
<?$APPLICATION->IncludeComponent(
    "advanced.search",
    "",
    array(
        "PAGE_SIZE" => "20",
        "CACHE_TIME" => "3600"
    )
);?>
```