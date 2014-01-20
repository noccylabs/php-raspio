<?php

namespace RaspIo\Device\Gpio;

interface IGpioMapper {

    public function export($pin);
    public function unexport($pin);
    public function get($pin);

}
