<?php

namespace Fonsecas72\DebugExtension;

use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Behat\EventDispatcher\Event\ScenarioLikeTested;
use Behat\Behat\EventDispatcher\Event\ExampleTested;
use Behat\Mink\Mink;

class DebugListener implements EventSubscriberInterface
{
    const DEBUG_TAG = 'debug';

    /** @var Mink  */
    private $mink;
    private $defaultSessionName;
    private $useScenarioFolder;

    public function __construct(Mink $mink, $useScenarioFolder = false)
    {
        $this->mink = $mink;
        $this->useScenarioFolder = $useScenarioFolder;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ScenarioTested::BEFORE   => array('beforeScenarioEnableDebug', 10),
            ExampleTested::BEFORE   => array('beforeScenarioEnableDebug', 10),
            ScenarioTested::AFTER  => array('afterScenarioEnableDebug', -10),
        );
    }

    public function beforeScenarioEnableDebug(ScenarioLikeTested $event)
    {
        $this->defaultSessionName = $this->mink->getDefaultSessionName();
        if ($this->hasDebugTag($event) || $this->mink->getDefaultSessionName() === static::DEBUG_TAG) {
            $this->mink->setDefaultSessionName('debug');
            $this->mink->getSession()->getDriver()->resetCounter();
            if ($this->useScenarioFolder) {
                $this->mink->getSession()->getDriver()->setScenarioPath(str_replace(' ', '_', $event->getScenario()->getTitle()));
            }
        }
    }
    public function afterScenarioEnableDebug()
    {
        if ($this->defaultSessionName !== $this->mink->getDefaultSessionName()) {
            $this->mink->setDefaultSessionName($this->defaultSessionName);
        }
        if ($this->defaultSessionName === static::DEBUG_TAG){
            $this->mink->getSession()->getDriver()->giffy();
        }
    }

    private function hasDebugTag($event)
    {
        return $event->getScenario()->hasTag(self::DEBUG_TAG) || $event->getFeature()->hasTag(self::DEBUG_TAG);
    }
}
