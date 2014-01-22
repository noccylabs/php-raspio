<?php

namespace RaspIo\Device\Gpio;

/**
 * This interface is for exported Gpio pins.
 *
 */
interface IGpioExport {

    /**
     * Set the pin direction
     *
     * @param string $direction One of "in" or "out"
     * @return IGpioExport Self ($this)
     */
    public function setDirection($direction);
    
    /**
     * @brief Get the pin direction.
     *
     * @return string The pin direction
     */
    public function getDirection();
    
    /**
     * @brief Set the pin state.
     *
     * @param int $value The pin state
     * @return IGpioExport Self ($this)
     */
    public function setValue($value);
    
    /**
     * @brief Get the pin state.
     *
     * @return int The pin state
     */
    public function getValue();

    public function setInterruptEdge($value);
    
    public function getInterruptEdge();

}
