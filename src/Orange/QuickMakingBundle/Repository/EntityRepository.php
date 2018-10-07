<?php

namespace Orange\QuickMakingBundle\Repository;

use Doctrine\ORM\EntityRepository as Repository;

/**
 * EntityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntityRepository extends Repository
{

	protected $_ids;
	protected $_states;
	
	/**
	 * @var \Orange\MainBundle\Entity\Utilisateur
	 */
	protected $_user;
	
	public function setParameters($params = array('ids' => null, 'states' => null, 'user' => null)) {
		$this->_ids		= $params['ids'];
		$this->_states	= $params['states'];
		$this->_user	= $params['user'];
	}
	
	public function listAll() {
		return $this->createQueryBuilder('q')
			->where('q.etat != :etat')
			->setParameter('etat', $this->_states['entity']['supprime'])
			->getQuery()
			->execute();
	}
	
	public function listAllQueryBuilder() {
		return $this->createQueryBuilder('q')
			->where('q.etat != :etat')
			->setParameter('etat', $this->_states['entity']['supprime']);
	}
}
