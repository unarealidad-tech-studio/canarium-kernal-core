<?php

namespace CanariumCore\Mapper;

use Zend\Stdlib\AbstractOptions;

/**
 * Interface for CanariumCore\Mapper mappers
 */
interface MapperInterface
{
    /**
     * @param array|\Traversable|\stdClass $data
     * @return Entity
     */
    public function create(array $data);

    /**
     * @param string $id
     * @return Entity
     */
    public function fetch($id);

    /**
     * @return Collection
     */
    public function fetchAll(array $criteria = null, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param string $id
     * @param array|\Traversable|\stdClass $data
     * @return Entity
     */
    public function update($id, array $data);

    /**
     * @param string $id
     * @return bool
     */
    public function delete($id);

    /**
     * @return bool
     */
    public function save();
}
