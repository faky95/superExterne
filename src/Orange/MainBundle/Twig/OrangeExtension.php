<?php 
namespace Orange\MainBundle\Twig;


use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Orange\MainBundle\Entity\Statut;
use Twig\TwigFilter;

class OrangeExtension extends \Twig_Extension {

	/**
	 * @var \Twig_Environment
	 */
	private $twig;
	
	/**
	 * @var \Orange\QuickMakingBundle\Model\EntityManager
	 */
	private $em;
	
	/**
	 * @var array
	 */
	private $ids;
	
	/**
	 * @var array
	 */
	private $states;
	
	/**
	 * @var array
	 */
	private $types;

	/**
	 * @param ContainerAwareTrait $container
	 * @param array $ids
	 * @param array $states
	 * @param array $types
	 */
	public function __construct($container, $ids, $states, $types) {
		$this->twig = $container->get('twig');
		$this->em = $container->get('doctrine.orm.entity_manager');
		$this->ids = $ids;
		$this->states = $states;
		$this->types = $types;
	}
	
	/**
	 * {@inheritdoc}
	 */
    public function getFilters() {
        return array(
        		'show_status' => new TwigFilter('show_status', array($this, 'showStatus'), array('is_safe' => array('html'))),
        		'get_statut' => new TwigFilter('get_statut_for_action', array($this, 'getStatutForAction'), array('is_safe' => array('html'))),
        		'get_statut_tache' => new TwigFilter('get_statut_for_tache', array($this, 'getStatutForTache'), array('is_safe' => array('html'))),
        		'get_statut_signalisation' => new TwigFilter('get_statut_for_signalisation', array($this, 'getStatutForSignalisation'), array('is_safe' => array('html')))
        	);
    }
    
    /**
     * @param string $entity
     * @param string $column
     */
    public function stateEntity($entity, $column) {
    	return $this->showStatus($entity, $column);
    }
    
    /**
     * @param string $entity
     * @param string $column
     */
    public function showStatus($entity, $column) {
    	if(!$entity) {
    		return;
    	}
    	$reflect = new \ReflectionClass($entity);
    	$template = $this->twig->loadTemplate('OrangeMainBundle:Extra:status.html.twig');
    	return $template->renderBlock('status_'.strtolower($reflect->getShortName().'_'.$column), array(
    			'entity' => $entity, 'ids' => $this->ids, 'states' => $this->states, 'types' => $this->types
    		));
    }
    
    /**
     * @param \Orange\MainBundle\Entity\Action $entity
     */
    public function getStatutForAction($entity) {
    	return $this->em->getRepository('OrangeMainBundle:Statut')->getStatutForAction($entity);
    }
    
    /**
     * @param \Orange\MainBundle\Entity\Tache $entity
     */
    public function getStatutForTache($entity) {
    	return $this->em->getRepository('OrangeMainBundle:Statut')->getStatutForTache($entity);
    }
    
    /**
     *
     * @param \Orange\MainBundle\Entity\Signalisation $entity
     */
    public function getStatutForSignalisation($entity) {
    	return $this->em->getRepository ( 'OrangeMainBundle:Statut' )->getStatutForSignalisation ( $entity );
    }

    public function getName() {
        return 'orange_extension';
    }
}