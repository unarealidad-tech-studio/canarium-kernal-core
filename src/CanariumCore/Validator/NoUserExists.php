<?php

namespace CanariumCore\Validator;
use Zend\Validator\AbstractValidator;
use Doctrine\ORM\EntityManager;

class NoUserExists extends AbstractValidator
{
    protected $key;
    protected $user;
    protected $entityManager;

    /**
     * Error constants
     */
    const ERROR_NO_RECORD_FOUND = 'noRecordFound';
    const ERROR_RECORD_FOUND    = 'recordFound';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record matching the input was found",
        self::ERROR_RECORD_FOUND    => "A record matching the input was found",
    );

    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setUser($user=null)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function query($value)
    {
        $result = false;

        switch ($this->getKey()) {
            case 'email':
                $result = $this->findUserByEmail($value);
                break;
            default:
                throw new \Exception('Invalid key used in User validator');
                break;
        }

        return $result;
    }

    public function isValid($value)
    {
        $valid = true;
        $this->setValue($value);

        $result = $this->query($value);
        if ($result) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }

    public function findUserByEmail($value)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('u')
                     ->from('CanariumCore\Entity\User', 'u')
                     ->andWhere('u.email=:email')
                        ->setParameter(':email', $value);

        if ($this->getUser()) {
            $queryBuilder->andWhere('u.id<>:id')
                         ->setParameter(':id', $this->getUser()->getId());
        }

        $result = $queryBuilder->getQuery()->getResult();

        if ($result) {
            return $result;
        }

        return false;
    }

}
