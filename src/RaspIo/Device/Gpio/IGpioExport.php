<?php

namespace RaspIo\Device\Gpio;

interface IGpioExport {

    public function setDirection($direction);
    public function getDirection();
    public function setValue($value);
    public function getValue();

/*
    public function hasInterrupt();
    public function enableInterrupt($edge);
    public function disableInterrupt();
    public function waitForInterrupt();
    public function getInterrupt();
*/

}
