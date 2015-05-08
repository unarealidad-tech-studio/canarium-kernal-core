<?php
namespace CanariumCore\Validator;
class WordCount extends \Zend\Validator\AbstractValidator
{
    const MSG_MINIMUM = 'msgMinimum';
    const MSG_MAXIMUM = 'msgMaximum';

   	protected $messageVariables = array(
        'min' => array('options' => 'min'),
        'max' => array('options' => 'max'),
    );

    protected $messageTemplates = array(
        self::MSG_MINIMUM => "must be at least '%min%'",
        self::MSG_MAXIMUM => "must be no more than '%max%'"
    );

	protected $options = array(
        'min'      => 0,       // Minimum length
        'max'      => null,    // Maximum length, null if there is no length limitation
    );

	public function __construct($options = array())
    {
        if (!is_array($options)) {
            $options     = func_get_args();
            $temp['min'] = array_shift($options);
            if (!empty($options)) {
                $temp['max'] = array_shift($options);
            }

            $options = $temp;
        }

        parent::__construct($options);
    }

	public function getMin()
    {
        return $this->options['min'];
    }

	public function setMin($min)
    {
        if (null !== $this->getMax() && $min > $this->getMax()) {
            throw new Exception\InvalidArgumentException("The minimum must be less than or equal to the maximum length, but $min >"
                                            . " " . $this->getMax());
        }

        $this->options['min'] = max(0, (int) $min);
        return $this;
    }

	public function getMax()
    {
        return $this->options['max'];
    }

	public function setMax($max)
    {
        if (null === $max) {
            $this->options['max'] = null;
        } elseif ($max < $this->getMin()) {
            throw new Exception\InvalidArgumentException("The maximum must be greater than or equal to the minimum length, but "
                                            . "$max < " . $this->getMin());
        } else {
            $this->options['max'] = (int) $max;
        }

        return $this;
    }

    public function isValid($value)
    {
        $this->setValue($value);

        if (str_word_count($value) < $this->getMin()) {
            $this->error(self::MSG_MINIMUM);
            return false;
        }

        if (str_word_count($value) > $this->getMax()) {
            $this->error(self::MSG_MAXIMUM);
            return false;
        }

        return true;
    }
}