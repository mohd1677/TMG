<?php
/**
 * InvalidFormException
 */
namespace TMG\Api\ApiBundle\Exception;

use Symfony\Component\Form\Form;

/**
 * Class InvalidFormException
 *
 * @package Exception
 */
class InvalidFormException extends ClassicHttpException
{
    /**
     * The form object
     *
     * @var Form
     */
    protected $form;

    /**
     * The original form submission data
     *
     * @var array
     */
    protected $data;

    /**
     * [Constructor]
     *
     * @param string     $message  The Exception message
     * @param Form       $form     The Symfony form object that was invalid
     * @param \Exception $previous A previous exception that was thrown
     * @param int        $code     The error code
     */
    public function __construct($message = null, Form $form = null, $data = [], \Exception $previous = null, $code = 0)
    {
        parent::__construct(400, $message, $previous, array(), $code);

        $this->form = $form;
        $this->data = $data;
    }

    /**
     * Returns the Symfony form object
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Returns the original data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
