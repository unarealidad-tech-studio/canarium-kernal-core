<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CanariumCore\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="access_token")
 */
class AccessToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $access_token;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $expiry_date;

    /**
     * @ORM\ManyToOne(targetEntity="CanariumCore\Entity\User", cascade={"remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    public function getId() 
    {
        return $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
        return $this;
    }

    public function getAccessToken() 
    {
        return $this->access_token;
    }

    public function setAccessToken($token) 
    {
        $this->access_token =$token;
        return $this;
    }

    public function getExpiryDate() 
    {
        return $this->expiry_date;
    }

    public function setExpiryDate(\DateTime $date) 
    {
        $this->expiry_date = $date;
        return $this;
    }

    public function getUser() 
    {
        return $this->user;
    }
    
    public function setUser(\CanariumCore\Entity\User $user) 
    {
        $this->user = $user;
        return $this;
    }
}
