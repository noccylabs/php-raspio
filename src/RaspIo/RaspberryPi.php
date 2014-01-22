<?php

namespace RaspIo;

use RaspIo\Device\Device;
use IteratorAggregate;

abstract class RaspberryPi implements IteratorAggregate {

    private static $boards = [
        "RaspIo\\Board\\RaspberryRev2Board",
        "RaspIo\\Board\\RaspberryRev1Board"
    ];

    private $devices = [];
    private $devicealias = [];

    /**
     * @brief Return the logical to physical pin map.
     *
     * @return array The pin map
     */
    abstract public function getWiringMap();
    
    /**
     * @brief Return version/info on the current board.
     *
     * @return string The board type and revision
     */
    abstract public function getVersion();

    /**
     * @brief Main constructor.
     * Sets up some default aliases if the devices are present.
     */
    protected function __construct() {

        // register uart0 as uart
        if ((isset($this->uart0)) && (!isset($this->uart)))
            $this->registerAlias("uart","uart0");

        // register spi0 as spi
        if ((isset($this->spi0)) && (!isset($this->spi)))
            $this->registerAlias("spi","spi0");

        // register i2c0 as i2c
        if ((isset($this->i2c0)) && (!isset($this->i2c)))
            $this->registerAlias("i2c","i2c0");
    
    }

    /**
     * @brief Add a driver to the candidate stack.
     * The list contains the Rev1 and Rev2 boards already, but can be extended
     * to run on foreign hardware (beaglebone, udoo etc). The first candidate
     * that doesn't throw an exception is used.
     *
     * @param string $board The board driver class to add as a string
     */
    public static function addBoardDriver($board) {
    
        self::$boards[] = $board;
    
    }
    
    /**
     * @brief Adds an emulated RaspberryPi Rev2 board to the candidate stack.
     * For debugging and testing on virtual hardware.
     *
     */
    public static function addEmulationBoardDriver() {
        
        self::addBoardDriver("RaspIo\\Board\\EmulatedRev2Board");

    }
 
    /**
     * @brief Get the main RaspIo instance.
     * In order to add your own custom board drivers, call addBoardDriver() or
     * addEmulationBoardDriver() before the first call to getInstance().
     *
     * @see RaspberryPi::addBoardDriver
     * @see RaspberryPi::addEmulationBoardDriver
     */
    public static function getInstance() {
    
        static $instance;
        
        if (!$instance) {
            
            $cpuinfo = self::getCpuInfo();
        
            foreach(self::$boards as $candidate) {
                try {
                    $board = new $candidate($cpuinfo);
                    break;
                } catch (\Exception $e) {
                    $board = null;
                }
            }
            
            $instance = $board;

        }

        return $instance;
    
    }

    /**
     * @brief Get an iterator for the devices provided.
     *
     * @return ArrayIterator The iterator
     */
    public function getIterator() {
    
        return new \ArrayIterator($this->devices);
    
    }
    
    /**
     * @brief Get basic CPU information of the Raspberry Pi
     *
     * @return object A populated object having the keys hw_rev, serial and hardware.
     */
    public static function getCpuInfo() {
    
		$cpuinfo = explode("\n",file_get_contents("/proc/cpuinfo"));
		$hw_rev = null;
		$pi_serial = null;
		$pi_hardware = null;
	
		foreach($cpuinfo as $line) {
			if (strpos($line,":")!==false) {
				list($k,$v) = explode(":",$line,2);
				switch(trim(strtolower($k))) {
					case "revision":
						$hw_rev = trim($v);
						break;
					case "serial":
						$pi_serial = trim($v);
						break;
					case "hardware":
						$pi_hardware = trim($v);
						break;
				}
			}
		}
	
		return (object)[
		    "hw_rev" => $hw_rev,
		    "serial" => $pi_serial,
		    "hardware" => $pi_hardware
		];
    
    }
    
    /**
     * @brief Register a device.
     *
     * @note Replacing a registered device instance causes undefined behavior.
     *
     * @param string $id The id to use.
     * @param RaspIo\Device\Device $device The device to add
     */
    public function registerDevice($id, Device $device) {
    
        $this->devices[$id] = $device;
    
    }
    
    /**
     * @brief Register a device alias.
     * For example, the serial uart should be 'uart0' in order to be future-proof
     * and to allow software uarts to be added alongside. In this case, the
     * default uart 'uart0' is normally aliased to 'uart' by the board driver
     * by calling $this->registerAlias("uart","uart0"). The same is valid for
     * cases where there are f.ex. multiple gpio controllers.
     *
     * @param string $alias The alias to register
     * @param string $id The device id that this is to be an alias for.
     */
    public function registerAlias($alias, $id) {
    
        $this->devicealias[$alias] = $id;
    
    }
    
    /**
     * @brief Get a list of the registered aliases.
     *
     * @return array
     */
    public function getRegisteredAliases() {
    
        return $this->devicealias;
    
    }

    /**
     * @brief Get a list of the registered devices.
     *
     * @return array
     */
    public function getRegisteredDevices() {
    
        return $this->devices;
    
    }
    
    /**
     * @brief Get the instance of a specific device.
     *
     * @see RaspberryPi::__get
     *
     * @param string $id The device id, f.ex. 'uart0' or 'gpio'
     * @param bool $aliased If false, aliases won't be resolved.
     * @return RaspIo\Device\Device|null The device instance.
     */
    public function getDevice($id,$aliased=true) {
    
        if ($aliased)
            if (array_key_exists($id,$this->devicealias))
                $id = $this->devicealias[$id];
    
        if (!array_key_exists($id,$this->devices))
            throw new \Exception("No such device {$id}");
        
        return $this->devices[$id];
        
    }

    /**
     * @brief Get the instance of a specific device.
     *
     * @see RaspberryPi::getDevice
     *
     * @param string $id The device id, f.ex. 'uart0' or 'gpio'
     * @param bool $aliased If false, aliases won't be resolved.
     * @return RaspIo\Device\Device|null The device instance.
     */
    public function __get($id) {
    
        return $this->getDevice($id);
    
    }
    
    /**
     * @brief Check if a device with the specific id exists.
     *
     * @see RaspberryPi::__isset
     *
     * @param string $id The device id, f.ex. 'uart0' or 'gpio'
     * @return bool True if there is a usable device with the specified id.
     */    
    public function hasDevice($id) {
        
        return (array_key_exists($id,$this->devices));   
        
    }
    
    /**
     * @brief Check if a device with the specific id exists.
     *
     * @see RaspberryPi::hasDevice
     *
     * @param string $id The device id, f.ex. 'uart0' or 'gpio'
     * @return bool True if there is a usable device with the specified id.
     */    
    public function __isset($id) {
    
        return $this->hasDevice($id);
    
    }
 
    
}

