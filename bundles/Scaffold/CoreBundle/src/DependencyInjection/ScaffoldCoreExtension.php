<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DependencyInjection;

use Scaffold\CoreBundle\Context\SiteContext;
use Scaffold\CoreBundle\DTO\Config\PrivacyPolicyConfiguration;
use Scaffold\CoreBundle\DTO\Config\SiteConfiguration;
use Scaffold\CoreBundle\DTO\Config\TermsAndConditionsConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ScaffoldCoreExtension extends Extension implements PrependExtensionInterface
{
    /**
     * This method is called before any bundle configuration is loaded.
     * We use it to tell Twig: "Hey, I have templates in this folder!"
     */
    public function prepend(ContainerBuilder $container): void
    {
        // If Twig isn't installed in the app, do nothing
        if (!$container->hasExtension('twig')) {
            return;
        }

        // 1. Register the templates folder
        $container->prependExtensionConfig('twig', [
            'globals' => [
                'scaffold_core' => '@' . SiteContext::class,
            ],
            'paths' => [
                __DIR__ . '/../../templates' => 'ScaffoldCore',
            ],
        ]);

        // 2. Register Assets (Add this!)
        if ($container->hasExtension('framework')) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [
                        // Logic: Map the 'assets' folder to the 'scaffold-core' namespace
                        // result: assets/scaffold.js -> available as 'scaffold-core/scaffold.js'
                        __DIR__ . '/../../assets' => 'scaffold-core',
                    ],
                ],
            ]);
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        // 1. Validate Config
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // 2. Load Services (Commands, etc)
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $container->register(SiteConfiguration::class, SiteConfiguration::class)
            ->setArguments([
                '$name' => $config['site']['name'],
                '$strapline' => $config['site']['strapline'],
            ]);

        $container->register(PrivacyPolicyConfiguration::class, PrivacyPolicyConfiguration::class)
            ->setArguments([
                '$lastUpdated' => $config['privacy_policy']['last_updated'],
                '$contactEmail' => $config['privacy_policy']['contact_email'],
                '$siteUrl' => $config['privacy_policy']['site_url'],
            ]);

        $container->register(TermsAndConditionsConfiguration::class, TermsAndConditionsConfiguration::class)
            ->setArguments([
                '$lastUpdated' => $config['terms_and_conditions']['last_updated'],
                '$contactEmail' => $config['terms_and_conditions']['contact_email'],
                '$siteUrl' => $config['terms_and_conditions']['site_url'],
                '$jurisdiction' => $config['terms_and_conditions']['jurisdiction'],
                '$paymentProvider' => $config['terms_and_conditions']['payment_provider'],
            ]);
    }
}
