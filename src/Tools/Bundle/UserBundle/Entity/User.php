<?php
namespace Tools\Bundle\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;

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

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookURL;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, options={"default" = "default.jpg"})
     */
    protected $photo;
    
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
     * @param string $facebookURL
     * @return void
     */
    public function setFacebookURL($facebookURL=null)
    {
        $this->facebookURL = $facebookURL;        
    }

    /**
     * @return string
     */
    public function getFacebookURL()
    {
        return $this->facebookURL;
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
    
    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->getWebPhotoPath();
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo = null)
    {
        if(is_null($this->photo))
        {
            $photo = $this->defaultPhoto;
        }
        $this->photo = $photo;
    }
    
    public function getAbsolutePhotoPath()
    {
        return null === $this->photo ? null : $this->getUploadRootDir().'/'.$this->photo;
    }

    public function getWebPhotoPath()
    {
        return null === $this->photo ? null : $this->getUploadDir().'/'.$this->photo;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/avatars';
    }
    
    /**
     * @return boolean
     */  
    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return false;
        }

        $name = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();

        if($this->file->move($this->getUploadRootDir(), $name)){
            
            if(!is_null($this->photo) && $this->photo != $this->defaultPhoto)
            {
                $this->removePhoto();
            }
            
            $this->photo = $name;  
            $this->file = null;
            return true;
        } else {
            $this->file = null;
            return false;
        }     
    }
    
    public function copyfbAvatar()
    {
        $url = "http://graph.facebook.com/".$this->getFacebookID()."/picture?type=large";
        $name = sha1(uniqid(mt_rand(), true)).'.jpg';
        if(copy($url, $this->getUploadRootDir().'/'.$name)){
            $this->setPhoto($name); 
            return true;
        }
        return false;
    }
    
    public function removePhoto()
    {
        $fs = new Filesystem();
        $fs->remove($this->getUploadRootDir().'/'.$this->photo);
        $this->photo = $this->defaultPhoto;
    }
}