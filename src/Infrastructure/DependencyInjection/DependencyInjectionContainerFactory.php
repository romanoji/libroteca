<?php
declare(strict_types=1);

namespace RJozwiak\Libroteca\Infrastructure\DependencyInjection;

use RJozwiak\Libroteca\UI\Web\Application;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Exception\EnvParameterException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DependencyInjectionContainerFactory
{
    private const CONFIG_PATH = __DIR__.'/Resources';
    private const CONFIG_FILE = 'config.yml';

    /**
     * @param ParameterBagInterface|null $parameterBag
     * @param bool $debugMode
     * @return ContainerInterface
     * @throws EnvParameterException
     * @throws InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function create(
        ParameterBagInterface $parameterBag = null,
        bool $debugMode = false
    ) : ContainerInterface {
        if (!$debugMode) {
            $containerConfigCache = new ConfigCache(self::containerCachePath(), $debugMode);

            if (!$containerConfigCache->isFresh()) {
                self::cacheContainer($parameterBag, $containerConfigCache);
            }

            return self::cachedContainer();
        }

        return self::createContainer($parameterBag);
    }

    /**
     * @param ParameterBagInterface|null $parameterBag
     * @return ContainerBuilder
     * @throws InvalidArgumentException
     */
    private static function createContainer(
        ParameterBagInterface $parameterBag = null
    ) : ContainerBuilder {
        $container = new ContainerBuilder($parameterBag);

        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_PATH));
        $loader->load(self::CONFIG_FILE);

        return $container;
    }

    /**
     * @param ParameterBagInterface|null $parameterBag
     * @param ConfigCache $containerConfigCache
     * @throws InvalidArgumentException
     * @throws \RuntimeException
     * @throws EnvParameterException
     */
    private static function cacheContainer(
        ParameterBagInterface $parameterBag = null,
        ConfigCache $containerConfigCache
    ) {
        $container = self::createContainer($parameterBag);
        $container->compile();

        $dumper = new PhpDumper($container);
        $containerConfigCache->write(
            $dumper->dump([
                'namespace' => __NAMESPACE__,
                'class' => 'CachedContainer'
            ]),
            $container->getResources()
        );
    }

    /**
     * @return CachedContainer
     */
    private static function cachedContainer() : CachedContainer
    {
        require_once self::containerCachePath();
        return new CachedContainer();
    }

    /**
     * @return string
     */
    private static function containerCachePath() : string
    {
        return Application::cacheDir().'/dependency_injection/container.php';
    }
}
