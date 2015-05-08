<?php
namespace CanariumCore\View\Helper\Filter;

use Zend\View\Helper\AbstractHelper;

class Url extends AbstractHelper
{

    public function __invoke($value){
        $filter = new \CanariumCore\Filter\Url();
		return $filter->filter($value);
    }
}