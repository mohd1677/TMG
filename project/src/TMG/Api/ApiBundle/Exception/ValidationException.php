<?php
/**
 * Validation Exception
 */

namespace TMG\Api\ApiBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ValidationException
 * @package TMG\Api\ApiBundle\Exception
 */
class ValidationException extends BadRequestHttpException
{
    private $aErrors = array();
    
    public function __construct(ConstraintViolationList $errors)
    {
        parent::__construct($errors->__toString());
        
        foreach ($errors as $error) {
            $this->aErrors[$error->getPropertyPath()] = $error->getMessage();
        }
    }
    
    /**
     * returns an array representation of the validation errors where the key is the
     * property path and the value is the error message
     * @return array
     */
    public function getErrors()
    {
        return $this->aErrors;
    }
}
