<?php

namespace RaspIo\Board;

use RaspIo\RaspberryPi;
use RaspIo\Device\DummyDevice;
use RaspIo\Device\Gpio\EmulatedGpioMapper;
use RaspIo\Device\Serial\UartDevice;

class EmulatedRev2Board extends RaspberryPi {

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

    public function __construct() {
        error_log("*****************************************************************");
        error_log("*                                                               *");
        error_log("*  Warning: RaspIo is using the emulation driver. This likely   *");
        error_log("*  means that no compatible hardware was found. Any GPIO or     *");
        error_log("*  other devices available on the current hardware will not be  *");
        error_log("*  used or modified in any way.                                 *");
        error_log("*                                                               *");
        error_log("*****************************************************************");
        $this->registerDevice("gpio", new EmulatedGpioMapper($this->getWiringMap()));
        $this->registerDevice("uart0", new DummyDevice());
        $this->registerDevice("spi0", new DummyDevice());
        $this->registerDevice("spi1", new DummyDevice());
        $this->registerDevice("i2c0", new DummyDevice());
        parent::__construct();
    }

    public function getWiringMap() {
        return $this->wiring_to_gpio;
    }

    public function getVersion() {
        return "Emulated Raspberry Pi Rev2";
    }

}


