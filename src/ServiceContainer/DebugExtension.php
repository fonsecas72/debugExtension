<?php

namespace Fonsecas72\DebugExtension\ServiceContainer;

use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Testwork\ServiceContainer\Extension;
use Fonsecas72\DebugExtension\ServiceContainer\Driver\DebugFactory;
use Symfony\Component\DependencyInjection\Reference;
use Behat\MinkExtension\ServiceContainer\MinkExtension;

class DebugExtension implements Extension
{
    public function initialize(ExtensionManager $extensionManager)
    {
        if (null !== $minkExtension = $extensionManager->getExtension('mink')) {
            $minkExtension->registerDriverFactory(new DebugFactory());
        }
    }
    
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition('Fonsecas72\DebugExtension\DebugListener', array(
            new Reference(MinkExtension::MINK_ID),
            '%debug.use_scenario_folder%'
        ));

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, array('priority' => 0));
        $container->setDefinition('mink.listener.debug', $definition);
        $container->setParameter('debug.screenshot_path', $config['screenshot_path']);
        $container->setParameter('debug.use_scenario_folder', $config['use_scenario_folder']);
    }

    public function getConfigKey()
    {
        return 'debug';
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()
                    ->scalarNode('screenshot_path')
                    ->isRequired()
                    ->end()
                ->end()
                ->children()
                    ->booleanNode('use_scenario_folder')
                ->end();
    }
    public function process(ContainerBuilder $container){}
}
