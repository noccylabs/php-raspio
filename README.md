RaspIo
======

## Installing

Install using composer:

    composer require "noccylabs/raspio:dev-master"

Or by cloning the github repository:

    git clone git@github.com:noccylabs/php-raspio

Note that composer will take care of the autoloading; if you clone the repository
you have to take care of that yourself.

## Getting Started

On a Raspberry Pi, just call on `RaspberryPi::getInstance()` to get an instance
of the board driver.

    use RaspIo\RaspberryPi;

    // Get an instance of the Pi
    $pi = RaspberryPi::getInstance();

`getInstance()` will find the first registered board driver that doesn't throw
an exception, make sure it's an instance of `RaspIo\RaspberryPi`, and then use
that for subsequent calls to `getInstance()`.

Devices are registered in the board driver constructor or in device drivers
with a call to the `registerDevice($id,$device)` method. This makes them

Devices can also be aliased, by calling `registerAlias($alias,$id)`. This is
used to define the default devices when more than one of the device type may
be available on the board.

The devices are then available via their respective properties as well as the
`getDevice()` method.

    // Like this
    $gpio = $pi->gpio;
    // Or this
    $gpio = $pi->getDevice("gpio");

You can get the info on the board detected by calling `getVersion()`:

    printf("Board: %s\n", $pi->getVersion());

Devices can also be iterated over, or accessed via `getRegisteredDevices()`
(and `getRegisteredAliases()`).

    printf("Devices:\n");
    foreach($pi as $device=>$obj) {
        printf("  %s => %s\n", $device, get_class($obj));
    }

    printf("Aliases:\n");
    foreach($pi->getRegisteredAliases() as $alias=>$device) {
        printf("  %s -> %s\n", $alias, $device);
    }

### ...on any other platform

If you will be using or testing cose using RaspIo on a platform that is not
supported, you can enable emulation.

    use RaspIo\RaspberryPi;
    
    // Add the emulated rev2 board. This must be done before the first
    // call to RaspberryPi::getInstance()
    RaspberryPi::addBoardDriver("RaspIo\\Board\\EmulatedRev2Board");
    // Get an instance of the Pi
    $pi = RaspberryPi::getInstance();


## Using the devices

### GPIO

The GPIO is available with the device id `gpio`, and it should be a class
implementing the interface `RaspIo\Device\Gpio\IGpioMapper`. It is normally an
instance of `RaspIo\Device\Gpio\GpioMapper`.

    // Get Pi instance
    $pi = RaspberryPi::getInstance();

    // Export and set direction
    $pin = $pi->gpio->export(0)->setDirection("out");
       
    // Write to the pin
    $pin->setValue(1);

Available methods are defined in `RaspIo\Device\Gpio\IGpioExport`:

 * `setDirection($dir)` and `getDirection()` to set the gpio direction to one
    of `in` or `out`.
 * `setValue($val)` and `getValue()` to set the gpio value to either `1` or
    `0`.

To unexport a pin, call on `unexport()` on the GpioMapper:

    // Unexport the pin
    $pi->gpio->unexport(0);

### UART

UART support is not yet implemented.

### Dummy Devices

Dummy devices are placeholders for not yet implemented devices.
