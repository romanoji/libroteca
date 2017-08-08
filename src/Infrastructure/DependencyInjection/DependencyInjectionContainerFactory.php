<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DependencyInjectionContainerFactory
{
    private const CONFIG_PATH = __DIR__.'/Resources';
    private const CONFIG_FILE = 'config.yml';

    /**
     * @return ContainerBuilder
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public static function create()
    {
        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_PATH));
        $loader->load(self::CONFIG_FILE);

        return $container;
    }
}
