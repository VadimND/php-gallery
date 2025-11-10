<?php

// Create abstract class
abstract class Vehicle {
    protected $brand;
    protected $model;
    protected $speed = 0;
    
    public function __construct($brand, $model) {
        $this->brand = $brand;
        $this->model = $model;
    }
    
    abstract public function start();
    abstract public function stop();
    
    public function getInfo() {
        return "{$this->brand} {$this->model}";
    }
    
    public function getSpeed() {
        return $this->speed;
    }
}

// Create real class with methods 
class Car extends Vehicle {
    private $fuelLevel;
    
    public function __construct($brand, $model, $fuelLevel = 100) {
        parent::__construct($brand, $model);
        $this->fuelLevel = $fuelLevel;
    }
    
    public function start() {
        if ($this->fuelLevel > 0) {
            $this->speed = 10;
            return "Двигатель автомобиля {$this->getInfo()} запущен";
        }
        return "Нет топлива!";
    }
    
    public function stop() {
        $this->speed = 0;
        return "Автомобиль {$this->getInfo()} остановлен";
    }
    
    public function accelerate($amount) {
        $this->speed += $amount;
        $this->fuelLevel -= $amount * 0.1;
        return "Скорость: {$this->speed} км/ч";
    }
}

// Create new object
$car = new Car("Toyota", "Camry", 80);
echo $car->start() . "\n";
echo $car->accelerate(50) . "\n";
echo $car->getInfo() . "\n";