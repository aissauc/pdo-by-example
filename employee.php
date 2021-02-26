<?php

class Employee {
    
    public $id;
    public $name;
    public $age;
    public $salary;
    public $tax;

    public function __construct()
    {
        $this->salary - ($this->salary * $this->tax / 100);
    }

}