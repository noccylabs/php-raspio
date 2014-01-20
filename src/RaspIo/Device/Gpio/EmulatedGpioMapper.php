<?php

namespace RaspIo\Device\Gpio;

use RaspIo\Device\Device;

class EmulatedGpioMapper extends Device {

    private $pinmap = [];
    private $pininst = [];

    public function __construct(array $pinmap) {
        $this->pinmap = $pinmap;
    }

    public function export($pin) {
        
        // Get the GPIO index for the requested pin
        $gpio_pin = $this->pinmap[$pin];
        // Export the pin
        try {
            $this->pininst[$pin] = new EmulatedGpioExport($gpio_pin);
        } catch (\Exception $e) {
            error_log("GPIO Export Error: ".$e->getMessage());
            return null;
        }
        
        return $this->get($pin);
        
    }
    
    public function unexport($pin) {
    
        // Unexport the pin
        unset($this->pininst[$pin]);
    
    }
    
    public function get($pin) {
    
        return $this->pininst[$pin];
    
    }

}
