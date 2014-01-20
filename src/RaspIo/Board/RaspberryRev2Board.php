<?php

namespace RaspIo\Board;

use RaspIo\RaspberryPi;
use RaspIo\Device\DummyDevice;
use RaspIo\Device\Gpio\GpioMapper;

class RaspberryRev2Board extends RaspberryPi {

	private $wiring_to_gpio = [
		17, 18, 27, 22, 23,
		24, 25, 4, 2, 3,
		8, 7, 10, 9, 11,
		14, 15, 28, 29, 30,
		31
	];

	private $gpio_to_wiring = [
		null, null, 8, 9, null,
		null, null, 11, 10, 13, 
		12, 14, null, null, 15,
		16, null, 0, 1, null, 
		null, null, 3, 4, 5,
		6, null, null, 17, 18,
		19, 20
	];

    public function __construct($cpuinfo) {
    
        if ($cpuinfo->hw_rev != "000d")
            throw new \Exception();

        $this->registerDevice("gpio", new GpioMapper($this->getWiringMap()));

        // Enumerate serial ports
    	$ports = array_merge(glob("/dev/ttyAMA*"),glob("/dev/ttyACM*"),glob("/dev/ttyUSB*"));
        $n = 0;
        foreach($ports as $port) {
            $this->registerDevice("uart{$n}", new DummyDevice());
            $this->registerAlias(basename(strtolower($port)), "uart{$n}");
        }
        
        parent::__construct();

    }

    public function getWiringMap() {
        return $this->wiring_to_gpio;
    }
    
    public function getVersion() {
        return "Raspberry Pi Rev2";
    }

}

