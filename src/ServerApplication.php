<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server;

use PhpCollection\Map;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\Events\EventsApplication;
use ThinFrame\Monolog\MonologApplication;
use ThinFrame\Server\DependencyInjection\HybridExtension;

/**
 * ServerApplication
 *
 * @package ThinFrame\Server
 * @since   0.2
 */
class ServerApplication extends AbstractApplication
{
    /**
     * Get application name
     *
     * @return string
     */
    public function getName()
    {
        return $this->reflector->getShortName();
    }

    /**
     * Get application parents
     *
     * @return AbstractApplication[]
     */
    public function getParents()
    {
        return [
            new EventsApplication(),
            new MonologApplication(),
        ];
    }

    /**
     * Set different options for the container configurator
     *
     * @param ContainerConfigurator $configurator
     */
    protected function setConfiguration(ContainerConfigurator $configurator)
    {
        $configurator
            ->addResources(
                [
                    'Resources/config/services.yml',
                    'Resources/config/config.yml'
                ]
            )
            ->addExtension($hybridExtension = new HybridExtension())
            ->addCompilerPass($hybridExtension);
    }

    /**
     * Set application metadata
     *
     * @param Map $metadata
     *
     */
    protected function setMetadata(Map $metadata)
    {
        //noop
    }
}
