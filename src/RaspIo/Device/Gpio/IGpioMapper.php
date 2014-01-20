<?php

namespace RaspIo\Device\Gpio;

interface IGpioMapper {

    /**
     * @brief Export a pin, making it available via get()
     *
     * @return IGpioExport The exported pin or null
     */
    public function export($pin);
    
    /**
     * @brief Unexport a pin, making it no longer available
     *
     * @param int $pin The wiring/logical pin id (0-based)
     */
    public function unexport($pin);
    
    /**
     * @brief Get access to an exported pin.
     *
     * @return IGpioExport The exported pin or null
     */
    public function get($pin);

}
