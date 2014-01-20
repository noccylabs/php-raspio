<?php

namespace RaspIo\Board;

use RaspIo\RaspberryPi;

class RaspberryRev1Board extends RaspberryPi {

    public function __construct($cpuinfo) {
        if ($cpuinfo->hw_rev != "0002")
            throw new \Exception();
    }

    public function getWiringMap() {
        return [];
    }
    
    public function getVersion() {
        return "Raspberry Pi Rev1";
    }

}

