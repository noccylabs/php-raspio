<?php

require_once __DIR__."/../vendor/autoload.php";

use RaspIo\RaspberryPi;

RaspberryPi::addBoardDriver("RaspIo\\Board\\EmulatedRev2Board");

$pi = RaspberryPi::getInstance(true);
printf("Board: %s\n", $pi->getVersion());

printf("Devices:\n");
foreach($pi as $device=>$obj) {
    printf("  %s => %s\n", $device, get_class($obj));
}

printf("Aliases:\n");
foreach($pi->getRegisteredAliases() as $alias=>$device) {
    printf("  %s -> %s\n", $alias, $device);
}
