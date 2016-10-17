<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);

        date_default_timezone_set('America/New_York');
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new RaulFraile\Bundle\LadybugBundle\RaulFraileLadybugBundle(),

            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

            new Ekino\Bundle\NewRelicBundle\EkinoNewRelicBundle(),

            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new \Aws\Symfony\AwsBundle(),

            new TMG\Api\DocsBundle\ApiDocsBundle(),
            new TMG\Api\GlobalBundle\ApiGlobalBundle(),
            new TMG\Api\UserBundle\ApiUserBundle(),
            new TMG\Api\ApiBundle\ApiBundle(),
            new TMG\Api\ContractBundle\TMGApiContractBundle(),
            new TMG\Api\DashboardBundle\ApiDashboardBundle(),
            new TMG\Api\ReputationBundle\TMGApiReputationBundle(),
            new TMG\Api\PropertiesBundle\TMGApiPropertiesBundle(),
            new TMG\Api\OAuthBundle\TMGApiOAuthBundle(),
            new TMG\Api\UtilityBundle\TMGApiUtilityBundle(),
            new TMG\Api\SocialBundle\TMGApiSocialBundle(),
            new TMG\Api\LegacyBundle\TMGApiLegacyBundle(),
            new TMG\Api\VideoBundle\TMGAPiVideoBundle(),
            new TMG\Console\CommandBundle\TMGConsoleCommandBundle(),
            new TMG\UtilitiesBundle\TMGUtilitiesBundle(),
            new TMG\Api\AdvertisementBundle\TMGApiAdvertisementBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
