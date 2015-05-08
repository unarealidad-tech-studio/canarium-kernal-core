<?php
namespace CanariumCore\Filter;

use Zend\Filter\FilterInterface;

class Url implements FilterInterface
{
    public function filter($value)
    {

		$filterChain = new \Zend\Filter\FilterChain();
		$filterChain->attach(new \Zend\Filter\StringToLower());
		$filterChain->attach(new \Zend\I18n\Filter\Alnum(true));

		$valueFiltered = str_replace(' ','-',$filterChain->filter($value));

        return $valueFiltered;
    }
}
