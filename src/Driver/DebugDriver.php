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
            mkdir($path, 0777, true);
        }
        $this->debugShotsGlobalPath = $path;

        parent::__construct($browserName, $desiredCapabilities, $wdHost);
    }

    public function giffy()
    {
        $frames = [];
        $durations = [];
        for ($index = 0; $index < $this->c; $index++) {
            if ($index === 0 || $index === 1 || $index === 2) {
                continue;
            }
            $frames[] = $this->getScreenshotDestination().DIRECTORY_SEPARATOR.$this->getSerializedName($this->i, $index);

            if ($this->c === $index) {
                $durations[] = 100;
                continue;
            }
            $durations[] = 30;
        }

        // Initialize and create the GIF !
        $gc = new \GifCreator\GifCreator();
        $gc->create($frames, $durations);
        $gifBinary = $gc->getGif();
        $gifFilename = $this->getScreenshotDestination().DIRECTORY_SEPARATOR.sprintf('%03d', $this->i).'_giffied.gif';
        file_put_contents($gifFilename, $gifBinary);
        echo PHP_EOL."| Gif captured ~> ".$gifFilename;
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
        return $this->getSerializedName($this->i, $this->c++);
    }
    private function getSerializedName($scenarioId, $stepId)
    {
        return 'shot_'.sprintf('%03d', $scenarioId).'_'.sprintf('%03d', $stepId).'.png';
    }
    private function saveScreenshot()
    {
        $screenshotFilename = $this->getScreenshotDestination().DIRECTORY_SEPARATOR.$this->getShotName();
        file_put_contents($screenshotFilename, parent::getScreenshot());
        echo PHP_EOL."| Screenshot captured ~> ".$screenshotFilename.PHP_EOL;
    }
    private function highlight($xpath)
    {
        $parent = parent::executeJsOnXpath($xpath, 'return {{ELEMENT}}.parentElement', true);
        $elementStyle = '{{ELEMENT}}.style';
        if ($parent !== null) {
            $elementStyle = '{{ELEMENT}}.parentElement.style';
        }

        $styles = parent::executeJsOnXpath($xpath, 'return '.$elementStyle, true);
        $myStyles = [];
        foreach ($styles as $value) {
            $myStyles[$value] = parent::executeJsOnXpath($xpath, 'return '.$elementStyle.'.'.$value, true);
        }

        parent::executeJsOnXpath($xpath, $elementStyle.' = "background-color: yellow; outline: 1px solid rgb(136, 255, 136)";', true);
        $this->saveScreenshot();
        if (empty($myStyles)) {
            parent::executeJsOnXpath($xpath, $elementStyle.' = null;', true);
            return;
        }
        foreach ($myStyles as $style => $value) {
            parent::executeJsOnXpath($xpath, $elementStyle.'.'.$style.' = '.$value.';', true);
        }
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
        $this->highlight($xpath);
        parent::click($xpath);
        $this->saveScreenshot();
    }


    public function getText($xpath)
    {
        $this->highlight($xpath);
        return parent::getText($xpath);
    }
    public function getAttribute($xpath, $name)
    {
        $this->highlight($xpath);
        return parent::getAttribute($xpath, $name);
    }
    public function getValue($xpath)
    {
        $this->highlight($xpath);
        return parent::getValue($xpath);
    }

    
    public function setValue($xpath, $value)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
        parent::setValue($xpath, $value);
        $this->saveScreenshot();
    }
    public function check($xpath)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
        parent::check($xpath);
        $this->saveScreenshot();
    }
    public function uncheck($xpath)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
        parent::uncheck($xpath);
        $this->saveScreenshot();
    }
    public function selectOption($xpath, $value, $multiple = false)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
        parent::selectOption($xpath, $value, $multiple);
        $this->saveScreenshot();
    }
    public function attachFile($xpath, $path)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
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
        $this->highlight($xpath);
        parent::blur($xpath);
        $this->saveScreenshot();
    }
    public function doubleClick($xpath)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
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
        $this->highlight($xpath);
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
        $this->highlight($xpath);
        parent::keyDown($xpath, $char, $modifier);
        $this->saveScreenshot();
    }
    public function keyPress($xpath, $char, $modifier = null)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
        parent::keyPress($xpath, $char, $modifier);
        $this->saveScreenshot();
    }
    public function keyUp($xpath, $char, $modifier = null)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
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
        $this->highlight($xpath);
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
        $this->highlight($xpath);
        parent::rightClick($xpath);
        $this->saveScreenshot();
    }
    public function submitForm($xpath)
    {
        $this->saveScreenshot();
        $this->highlight($xpath);
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
