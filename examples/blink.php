<?php

require_once __DIR__."/../vendor/autoload.php";

// GPIO0
define("LED_PIN",0);

use RaspIo\RaspberryPi;

// Enable emulation if no compatible board is found.
RaspberryPi::addBoardDriver("RaspIo\\Board\\EmulatedRev2Board");
// Get an instance of the Pi
$pi = RaspberryPi::getInstance();

// Export and set the mode of Wiring pin 0 to output
$pin = $pi->gpio->export(LED_PIN)->setDirection("out");

// Flash the led on pin 0 (gpio 17) 10 times
for($n = 0; $n < 10; $n++) {
	$pin->setValue(1);
	usleep(10000);
	$pin->setValue(0);
	usleep(1000000);
}
echo "\n";
