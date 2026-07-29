<?php

class Cat
{
    public string $name;
    public string $breed;

    public function getBreed(): string
    {
        return $this->breed;
    }

    function __construct($name, $breed = '')
    {
        $this->name = $name;
        $this->breed = $breed;
    }
}

$cat = new Cat("Pushinka");
$cat->breed = "siamese";
//echo $cat->getBreed();

class SmartCat extends Cat
{
    function setBreed() {
        $this->breed = "Bengal";
    }
}

$newcat = new SmartCat("Barsik");
$newcat->setBreed();
echo $newcat->getBreed();
echo $newcat->name;