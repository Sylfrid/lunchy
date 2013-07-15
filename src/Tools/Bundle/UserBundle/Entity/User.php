<?php
namespace Tools\Bundle\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tools_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    protected $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", nullable=true, length=255)
     */
    protected $adresse;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", nullable=true, length=255)
     */
    protected $ville;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codepostal", type="string", nullable=true, length=10)
     */
    protected $codepostal;
    
    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", nullable=true, length=255)
     */
    protected $facebookId;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    public function serialize()
    {
        return serialize(array($this->facebookId, parent::serialize()));
    }

    public function unserialize($data)
    {
        list($this->facebookId, $parentData) = unserialize($data);
        parent::unserialize($parentData);
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Retourne le nom complet de l'utilisateur (prenom + nom)
     * @return string
     */
    public function getNomComplet()
    {
        return $this->getPrenom() . ' ' . $this->getNom();
    }

    /**
     * @param string $facebookId
     * @return void
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        $this->setUsername($facebookId);
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param Array
     */
    public function setFBData($fbdata)
    {
        if (isset($fbdata['id'])) {
            $this->setFacebookId($fbdata['id']);
            $this->addRole('ROLE_FACEBOOK');
        }
        if (isset($fbdata['first_name'])) {
            $this->setPrenom($fbdata['first_name']);
        }
        if (isset($fbdata['last_name'])) {
            $this->setNom($fbdata['last_name']);
        }
        if (isset($fbdata['email'])) {
            $this->setEmail($fbdata['email']);
        }
    }
}