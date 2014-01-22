<?php

namespace RaspIo\Device\Gpio;

class EmulatedGpioExport implements IGpioExport {

    private $direction = "in";
    private $value = 0;

    public function __construct($gpio_pin) {

        $this->gpio_pin = $gpio_pin;
    
    }
    
    public function __destruct() {

    }
    
    public function setDirection($direction) {
    
        fprintf(STDERR,"GPIO%d.direction = %s\n", $this->gpio_pin, $direction);
        $this->direction = $direction;
        return $this;
    
    }

    public function getDirection() {
    
        return $this->direction;
    
    }
    
    public function setValue($value) {

        fprintf(STDERR,"GPIO%d.value = %d\n", $this->gpio_pin, $value);
        $this->value = (int)$value;
        return $this;
    
    }

    public function getValue() {
    
        return $this->value;
    
    }
    
    public function setInterruptEdge($edge) {
    
        fprintf(STDERR,"GPIO%d.edge = %s\n", $this->gpio_pin, $edge);
        return $this;
    
    }
    
    public function getInterruptEdge() { }
        
}
