<?php

namespace RaspIo\Device\Gpio;

class EmulatedGpioExport implements IGpioExport {

    public function __construct($gpio_pin) {

        $this->gpio_pin = $gpio_pin;
        error_log("Creating emulated GPIO{$gpio_pin}");
    
    }
    
    public function __destruct() {

    }
    
    public function setDirection($direction) {
    
        error_log("GPIO{$this->gpio_pin}.direction = {$direction}");
        return $this;
    
    }

    public function getDirection() {
    
    }
    
    public function setValue($value) {

        error_log("GPIO{$this->gpio_pin}.value = {$value}");
        return $this;
    
    }

    public function getValue() {
    
    }    
}
