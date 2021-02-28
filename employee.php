<?php

class Employee {
    
    private $id;
    private $name;
    private $age;
    private $address;
    private $salary;
    private $tax;

    public function __construct($name, $age, $address, $salary, $tax)
    {
        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
        $this->salary = $salary;
        $this->tax = $tax;
    }

    public function __get($prop) {
        return $this->$prop;
    }

    public function totalSalary() {

        return $this->salary - ($this->salary * $this->tax / 100);

    }

}