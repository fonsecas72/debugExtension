<?php

namespace Fonsecas72\DebugExtension;

use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Mink\Mink;
use Behat\Behat\EventDispatcher\Event\ScenarioLikeTested;
use Behat\Behat\EventDispatcher\Event\ExampleTested;
use Behat\Behat\EventDispatcher\Event\OutlineTested;
use Behat\Behat\EventDispatcher\Event\AfterOutlineTested;
use Behat\Behat\EventDispatcher\Event\BeforeOutlineTested;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;

class DebugListener implements EventSubscriberInterface
{
    const DEBUG_TAG = 'debug';

    /** @var Mink  */
    private $mink;
    private $defaultSessionName;
    private $useScenarioFolder;
    private $path = '';
    private $debugEnabled = false;
    
    public function __construct(Mink $mink, $useScenarioFolder = false)
    {
        $this->mink = $mink;
        $this->useScenarioFolder = $useScenarioFolder;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ScenarioTested::BEFORE => array(
                array('enableDebug'),
                array('saveDefaultSession'),
            ),
            ScenarioTested::AFTER  => array(
                array('giffy'),
                array('restoreDefaultSession'),
            ),

            // events for outlines / examples
            OutlineTested::BEFORE => array(
                array('setDestinationFolderWhenOutline'), // i'm setting the destination folder to be the outline title
            ),
            ExampleTested::BEFORE => array(
                array('enableDebug'),
                array('saveDefaultSession'),
            ),
            ExampleTested::AFTER   => array(
                array('giffy'),
            ),
            OutlineTested::AFTER => array(
                array('restoreDefaultSession'),
            ),
        );
    }

    public function saveDefaultSession()
    {
        if ($this->debugEnabled) {
            $this->defaultSessionName = $this->mink->getDefaultSessionName();
        }
    }
    public function setDestinationFolderWhenOutline($event)
    {
        $this->path = str_replace(' ', '_', $event->getOutline()->getTitle());
    }
    public function enableDebug($event)
    {
        if ($this->hasDebugTag($event) || $this->mink->getDefaultSessionName() === static::DEBUG_TAG) {
            $this->debugEnabled = true;
            $this->mink->setDefaultSessionName('debug');
            $this->mink->getSession()->getDriver()->resetCounter();
            if ($this->useScenarioFolder) {

                if ('Scenario' === $event->getNode()->getNodeType()) {
                    $this->path = str_replace(' ', '_', $event->getScenario()->getTitle());
                } 

                $this->mink->getSession()->getDriver()->setScenarioPath($this->path);
            }
        }
    }
    public function giffy()
    {
        if ($this->debugEnabled) {
            $this->mink->getSession()->getDriver()->giffy();
        }
    }
    public function restoreDefaultSession()
    {
        if ($this->debugEnabled) {
            $this->mink->setDefaultSessionName($this->defaultSessionName);
            $this->debugEnabled = false;
            $this->path = '';
        }
    }
    private function hasDebugTag($event)
    {
        if (method_exists($event, 'getOutline')) {
            return $event->getOutline()->hasTag(self::DEBUG_TAG) || $event->getFeature()->hasTag(self::DEBUG_TAG);
        }
        
        return $event->getScenario()->hasTag(self::DEBUG_TAG) || $event->getFeature()->hasTag(self::DEBUG_TAG);
    }
}
