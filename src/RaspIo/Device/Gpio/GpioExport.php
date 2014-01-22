<?php

namespace RaspIo\Device\Gpio;

class GpioExport implements IGpioExport {

    public function __construct($gpio_pin) {

        // Export the pin if it is not already    
        $this->gpio_pin = $gpio_pin;
        $this->export();

    }
    
    public function export() {

        $gpio_pin = $this->gpio_pin;
        // Export the pin
		if (!file_exists("/sys/class/gpio/gpio{$gpio_pin}")) {
            if (!is_writable("/sys/class/gpio/export"))
                throw new \RuntimeException("/sys/class/gpio/export is not writable!");
            // This is how we export the pin
    		file_put_contents("/sys/class/gpio/export",$gpio_pin);
            // delay for a maximum of 2s while waiting for the pin to
            // become ready.
		    $t = microtime(true);
		    while ((!is_writable("/sys/class/gpio/gpio{$gpio_pin}/direction")) && (microtime(true) < $t + 2))
			    usleep(10000);
			// Final check to see if we can access the pin
		    if (!is_writable("/sys/class/gpio/gpio{$gpio_pin}/direction"))
			    throw new \RuntimeException("Unable to export gpio pin {$gpio_pin}");
		}
		return $this;

    }
    
    public function unexport() {

        $gpio_pin = $this->gpio_pin;
        // Unexport the pin
		if (file_exists("/sys/class/gpio/gpio{$gpio_pin}")) {
    		file_put_contents("/sys/class/gpio/unexport",$gpio_pin);
        }
    
    }

    public function setDirection($direction) {

        file_put_contents("/sys/class/gpio/gpio{$this->gpio_pin}/direction", $direction);
        return $this;
    
    }

    public function getDirection() {

        return file_get_contents("/sys/class/gpio/gpio{$this->gpio_pin}/direction");
    
    }
    
    public function setValue($value) {

        file_put_contents("/sys/class/gpio/gpio{$this->gpio_pin}/value", $value);
        return $this;
    
    }

    public function getValue() {

        return file_get_contents("/sys/class/gpio/gpio{$this->gpio_pin}/value");
    
    }

    public function setInterruptEdge($edge) {
    
        file_put_contents("/sys/class/gpio/gpio{$this->gpio_pin}/edge", $edge);
        return $this;
    
    }
    
    public function getInterruptEdge() {
    
        return file_get_contents("/sys/class/gpio/gpio{$this->gpio_pin}/edge");
    
    }

}
