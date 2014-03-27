<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Controller;

use Soul\Form\ContactForm;

/**
 * Class ContactController
 *
 * @package Soul\Controller
 */
class ContactController extends Base
{
    public function indexAction()
    {
        $contactForm = new ContactForm();

        $this->view->form = $contactForm;
    }
}