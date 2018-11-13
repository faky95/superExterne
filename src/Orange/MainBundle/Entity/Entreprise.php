<?php
namespace Orange\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="license")
 * @ORM\Entity(repositoryClass="Orange\MainBundle\Repository\LicenseRepository")
 */
class License {

	/**
	 * @var number
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 */
	private $id;
	
	/**
	 * @var string
	 * @ORM\Column(name="libelle", type="string", length=100)
	 */
	private $libelle;
	
	/**
	 * @var number
	 * @ORM\Column(name="quota", type=integer)
	 */
	private $quota;
	
	
	public function getId() {
		return $this->id;
	}
	
	/**
	 * get libelle
	 * @return string
	 */
	public function getLibelle() {
		return $this->libelle;
	}
	
	/**
	 * set libelle
	 * @param string $libelle
	 * @return License
	 */
	public function setLibelle($libelle) {
		$this->libelle = $libelle;
		return $this;
	}
	
	/**
	 * get quota
	 * @return number
	 */
	public function getQuota() {
		return $this->quota;
	}
	
	/**
	 * set quota
	 * @param integer $quota
	 * @return License
	 */
	public function setQuota($quota) {
		$this->quota = $quota;
		return $this;
	}
	
	
}