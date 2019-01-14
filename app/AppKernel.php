<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppKernel extends Kernel
{
	
	/**
	 * @var string
	 */
	private $entreprise;
	
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        	new FOS\UserBundle\FOSUserBundle(),
        	new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        	new Ob\HighchartsBundle\ObHighchartsBundle(),
        	new Troopers\AlertifyBundle\TroopersAlertifyBundle(),
        	new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        	new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new FOS\RestBundle\FOSRestBundle(),
        	new JMS\SerializerBundle\JMSSerializerBundle(),
        	new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
        	new Nelmio\CorsBundle\NelmioCorsBundle(),
        	new Orange\MainBundle\OrangeMainBundle(),
        	new Orange\QuickMakingBundle\OrangeQuickMakingBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    	$loader->load(function (ContainerBuilder $container) {
    		$container->setParameter('container.autowiring.strict_mode', true);
    		$container->setParameter('container.dumper.inline_class_loader', true);
    		
    		$container->addObjectResource($this);
    	});
    		$loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
    
    /**
     * @return string
     */
    public function getEntreprise() {
    	return $this->entreprise;
    }
    
    /**
     * @param string $entreprise
     * @return AppKernel
     */
    public function setEntreprise($entreprise) {
    	$this->entreprise = $entreprise;
    	return $this;
    }
    
    public function getRootDir()
    {
    	return __DIR__;
    }
    
    public function getCacheDir()
    {
    	return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
    	return dirname(__DIR__).'/var/logs/'.($this->entreprise ? $this->entreprise : 'main');
    }

    /**
     * get WEB Dir
     */
    public function getWebDir() {
    	return dirname(__DIR__).'/web';
    }

    /**
     * Returns the kernel parameters.
     * @return array An array of kernel parameters
     */
    protected function getKernelParameters()
    {
    	return array_merge(parent::getKernelParameters(), array('kernel.web_dir' => $this->getWebDir()));
    }
}
