<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CanariumCore\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class User implements UserInterface, ProviderInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true,  length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var int
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="CanariumCore\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="Form\Entity\ParentData",mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id", onDelete="CASCADE")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    protected $forms;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $lastLogin;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $company;

    /**
     * @ORM\OneToMany(targetEntity="CanariumCore\Entity\UserMeta", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $meta;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $first_name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $last_name;

    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->forms = new ArrayCollection();
        $this->meta  = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName(){
        $displayName = $this->displayName;
        if (null === $displayName) {
            $displayName = $this->username;
        }
        if (null === $displayName) {
            $displayName = $this->email;
            $displayName = substr($displayName, 0, strpos($displayName, '@'));
        }

        return $displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return void
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles($array = true)
    {
        return $array ? $this->roles->getValues() : $this->roles;
    }



    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
        $this->roles->add($role);
    }

    public function addRoles($roles) {
        foreach ($roles as $role) {
            $this->roles->add($role);
        }
    }

    public function removeRole($roles) {
        foreach ($roles as $role) {
            $this->roles->removeElement($role);
        }
    }

    public function getForms(){
        return $this->forms;
    }

    public function getLastLogin(){
        return $this->lastLogin;
    }

    public function setLastLogin($i){
        $this->lastLogin = $i;
    }

    public function getCompany(){
        return $this->company;
    }

    public function setCompany($i){
        $this->company = $i;
    }

    public function getMeta() 
    {
        return $this->meta;
    }

    public function setMeta($meta_array) 
    {
        $this->meta = $meta_array;
    }
    public function addMeta(UserMeta $meta) 
    {
        $this->meta->add($meta);
    }

    public function getMetaValue($key) 
    { 
        foreach ($this->getMeta() as $meta) {
            if ($meta->getName() == $key) {
                return $meta->getValue();
            }
        }
    }
    public function setMetaValue($key, $value) 
    {
        foreach ($this->meta as $meta) {
            if ($meta->getName() == $key) {
                $meta->setValue($value);
            }
        }
    }

    /**
     * @return string
     */
    public function getFirstName() {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName() {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name) {
        $this->last_name = $last_name;
    }
}
