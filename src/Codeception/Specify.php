<?php
namespace Codeception;

trait Specify {

	function specify($specification, \Closure $callable)
	{
        $properties = get_object_vars($this);

        // cloning object properties
        foreach ($properties as $property => $val) {
            if (is_object($val)) {
                $this->$property = clone($val);
            }
        }
        $test = $callable->bindTo($this);
        $name = $this->getName();
        $this->setName($this->getName().'/ '.$specification);
        $result = $this->getTestResultObject();
        $result->stopOnFailure(false);
        try {
            $test();
        } catch (\PHPUnit_Framework_AssertionFailedError $f) {
            $result->addFailure(clone($this), $f, $result->time());
        }
        $result->stopOnFailure(true);

        // restoring object properties
        foreach ($properties as $property => $val) {
            $this->$property = $val;
        }
        $this->setName($name);
	}

}
