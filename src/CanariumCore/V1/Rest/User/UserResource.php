<?php
namespace CanariumCore\V1\Rest\User;

use ZF\Apigility\Doctrine\Server\Resource\DoctrineResource;

class UserResource extends DoctrineResource
{
    /**
     * @param array $data
     * @return mixed|\ZF\ApiProblem\ApiProblem
     */
    public function fetchAll($data = array())
    {
        if (!empty($data->current_logged_in)) {
            $auth = $this->getServiceManager()->get('zfcuser_auth_service');

            if ($auth->hasIdentity()) {
                $id = $auth->getIdentity()->getId();
            } else {
                $id = 0;
            }
            return parent::fetch($id);
        } else {
            return parent::fetchAll($data);
        }
    }


}
