<?php

namespace Fonsecas72\DebugExtension\Driver;

use Behat\Mink\Driver\Selenium2Driver;

class DebugDriver extends Selenium2Driver
{
    private $c = 0;
    private $i = 0;
    private $debugShotsGlobalPath = '';
    private $debugScenarioShotsPath = '';

    public function __construct($path, $browserName, $desiredCapabilities, $wdHost)
    {
        if (!file_exists($path)) {
            mkdir($path);
        }
        $this->debugShotsGlobalPath = $path;

        parent::__construct($browserName, $desiredCapabilities, $wdHost);
    }

    public function resetCounter()
    {
        $this->c = 0;
        $this->i++;
    }
    public function setScenarioPath($path)
    {
        $this->debugScenarioShotsPath = $path;
        if (!file_exists($this->getScreenshotDestination())) {
            mkdir($this->getScreenshotDestination());
        }
    }
    
    private function getScreenshotDestination()
    {
        if ($this->debugScenarioShotsPath === '') {
            return $this->debugShotsGlobalPath;
        }
        return $this->debugShotsGlobalPath.DIRECTORY_SEPARATOR.$this->debugScenarioShotsPath;
    }
    private function getShotName()
    {
        return 'screenshot_'.$this->i.'_'.$this->c++.'.png';
    }
    private function saveScreenshot()
    {
        $screenshotFilename = $this->getScreenshotDestination().DIRECTORY_SEPARATOR.$this->getShotName();
        file_put_contents($screenshotFilename, parent::getScreenshot());
        echo PHP_EOL."| Screenshot captured ~> ".$screenshotFilename;
    }



    // DO I REALLY HAVE TO DO THIS?

    public function visit($url)
    {
        $this->saveScreenshot();
        parent::visit($url);
        $this->saveScreenshot();
    }
    public function click($xpath)
    {
        $this->saveScreenshot();
        parent::click($xpath);
        $this->saveScreenshot();
    }
    public function setValue($xpath, $value)
    {
        $this->saveScreenshot();
        parent::setValue($xpath, $value);
        $this->saveScreenshot();
    }
    public function check($xpath)
    {
        $this->saveScreenshot();
        parent::check($xpath);
        $this->saveScreenshot();
    }
    public function uncheck($xpath)
    {
        $this->saveScreenshot();
        parent::uncheck($xpath);
        $this->saveScreenshot();
    }
    public function selectOption($xpath, $value, $multiple = false)
    {
        $this->saveScreenshot();
        parent::selectOption($xpath, $value, $multiple);
        $this->saveScreenshot();
    }
    public function attachFile($xpath, $path)
    {
        $this->saveScreenshot();
        parent::attachFile($xpath, $path);
        $this->saveScreenshot();
    }
    public function resizeWindow($width, $height, $name = null)
    {
        $this->saveScreenshot();
        parent::resizeWindow($width, $height, $name);
        $this->saveScreenshot();
    }
    public function back()
    {
        $this->saveScreenshot();
        parent::back();
        $this->saveScreenshot();
    }
    public function blur($xpath)
    {
        $this->saveScreenshot();
        parent::blur($xpath);
        $this->saveScreenshot();
    }
    public function doubleClick($xpath)
    {
        $this->saveScreenshot();
        parent::doubleClick($xpath);
        $this->saveScreenshot();
    }
    public function dragTo($sourceXpath, $destinationXpath)
    {
        $this->saveScreenshot();
        parent::dragTo($sourceXpath, $destinationXpath);
        $this->saveScreenshot();
    }
    public function evaluateScript($script)
    {
        $this->saveScreenshot();
        parent::evaluateScript($script);
        $this->saveScreenshot();
    }
    public function executeScript($script)
    {
        $this->saveScreenshot();
        parent::executeScript($script);
        $this->saveScreenshot();
    }
    public function focus($xpath)
    {
        $this->saveScreenshot();
        parent::focus($xpath);
        $this->saveScreenshot();
    }
    public function forward()
    {
        $this->saveScreenshot();
        parent::forward();
        $this->saveScreenshot();
    }
    public function keyDown($xpath, $char, $modifier = null)
    {
        $this->saveScreenshot();
        parent::keyDown($xpath, $char, $modifier);
        $this->saveScreenshot();
    }
    public function keyPress($xpath, $char, $modifier = null)
    {
        $this->saveScreenshot();
        parent::keyPress($xpath, $char, $modifier);
        $this->saveScreenshot();
    }
    public function keyUp($xpath, $char, $modifier = null)
    {
        $this->saveScreenshot();
        parent::keyUp($xpath, $char, $modifier);
        $this->saveScreenshot();
    }
    public function maximizeWindow($name = null)
    {
        $this->saveScreenshot();
        parent::maximizeWindow($name);
        $this->saveScreenshot();
    }
    public function mouseOver($xpath)
    {
        $this->saveScreenshot();
        parent::mouseOver($xpath);
        $this->saveScreenshot();
    }
    public function reload()
    {
        $this->saveScreenshot();
        parent::reload();
        $this->saveScreenshot();
    }
    public function rightClick($xpath)
    {
        $this->saveScreenshot();
        parent::rightClick($xpath);
        $this->saveScreenshot();
    }
    public function submitForm($xpath)
    {
        $this->saveScreenshot();
        parent::submitForm($xpath);
        $this->saveScreenshot();
    }
    public function switchToIFrame($name = null)
    {
        $this->saveScreenshot();
        parent::switchToIFrame($name);
        $this->saveScreenshot();
    }
    public function switchToWindow($name = null)
    {
        $this->saveScreenshot();
        parent::switchToWindow($name);
        $this->saveScreenshot();
    }
}
