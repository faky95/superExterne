<?php 

namespace Orange\MainBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\User\UserInterface;
use Orange\MainBundle\Entity\Signalisation;
use Orange\MainBundle\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class SignalisationVoter extends Voter
{
	
	const READ 	 	 = 'read';
	const UPDATE 	 = 'update';
	const DELETE	 = 'delete';
	
	private $em;
	
	protected $container;
	
	/**
	 * @param EntityManager $em
	 * @param ContainerInterface $container
	 */
	public function setServices(EntityManager $em, ContainerInterface $container) {
		$this->em = $em;
		$this->container = $container;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\Authorization\Voter\Voter::supports()
	 */
	protected function supports($attribute, $subject) {
		
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\Authorization\Voter\Voter::voteOnAttribute()
	 */
	protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
		
	}
	
	protected function getSupportedAttributes()
	{
		return array(self::READ, self::UPDATE, self::DELETE);
	}
	
	protected function getSupportedClasses()
	{
		return array('Orange\MainBundle\Entity\Signalisation');
	}
	
	protected function isGranted($attribute, $signalisation, $user = null)
	{
		$user = $this->container->get('security.token_storage')->getToken()->getUser();
		
		$instance = $signalisation->getInstance();
		
		// make sure there is a user object (i.e. that the user is logged in)
		if (!$user instanceof UserInterface) 
		{
			return false;
		}
		// double-check that the User object is the expected entity (this
		// only happens when you did not configure the security system properly)
		if (!$user instanceof Utilisateur) 
		{
			throw new \LogicException('The user is somehow not our User class!');
		}
		switch($attribute) 
		{
			
			case self::READ:
				if($user->hasRole('ROLE_SUPER_ADMIN') || $this->isInstanceAdministrateur($instance, $user))
				{
					return true;
				}
				if($user->hasRole('ROLE_MANAGER') && $this->isSignalisationManager($signalisation))
				{ 
					return true;
				}
				if($user->hasRole('ROLE_ANIMATEUR') && ($this->em->getRepository('OrangeMainBundle:Animateur')->findBy(array('instance' => $instance->getId(), 'utilisateur' => $user->getId()))))
				{
					return true;
				}
				if($user->hasRole('ROLE_SOURCE') && ($user->getId() === $signalisation->getSource()->getUtilisateur()->getId()))
				{
					return true;
				}
				break;
			case self::UPDATE:
				if($user->hasRole('ROLE_SUPER_ADMIN') || $this->isInstanceAdministrateur($instance, $user))
				{
					return true;
				}
				if($user->hasRole('ROLE_ANIMATEUR') && ($this->em->getRepository('OrangeMainBundle:Animateur')->findBy(array('instance' => $instance->getId(), 'utilisateur' => $user->getId()))))
				{
					return true;
				}
				if($user->hasRole('ROLE_SOURCE') && ($user->getId() === $signalisation->getSource()->getUtilisateur()->getId()))
				{
					return true;
				}
				break;
			case self::DELETE:
				if ($this->isInstanceAdministrateur($instance, $user) || $user->hasRole('ROLE_SUPER_ADMIN'))
				{
					return true;
				}
				break;
		}
		return false;
	}
	
	public function isInstanceAdministrateur($instance, $user)
	{
		$result = false;
		if($user->getIsAdmin())
		{
			$structureAdministrateurId = $user->getStructure()->getBuPrincipal()->getId();
			$structureInstance = $this->em->getRepository('OrangeMainBundle:Instance')->find($instance->getId())->getStructure();
			if(count($structureInstance) > 1)
			{
				$structure_id = array();
				foreach ($structureInstance as $structure)
				{
					array_push($structure_id, $structure->getId());
				}
				if(in_array($structureAdministrateurId, $structure_id))
				{
					$result = true;
				}
			}
		}
		return $result;
	}
	
	public function isSignalisationManager($signalisation)
	{
		$result = false;
		$animateurSignalisation = $this->em->getRepository('OrangeMainBundle:SignalisationAnimateur')->findOneBy(array('signalisation' => $signalisation->getId(), 'actif' => true));
		if($animateurSignalisation)
		{
			$structure = $animateurSignalisation->getUtilisateur()->getStructure();
			$managerAnimateur = $this->em->getRepository('OrangeMainBundle:Utilisateur')->findBy(array('manager' => true, 'structure' => $structure->getId()));
			if($managerAnimateur !== NULL)
			{
				$managerPlusTwo = $this->em->getRepository('OrangeMainBundle:Utilisateur')->findBy(array('manager' => true, 'structure' => $structure->getParent()->getId()));
			}
			if($managerAnimateur || $managerPlusTwo)
			{
				$result = true;
			}
		}
		return $result;
	}
	
}