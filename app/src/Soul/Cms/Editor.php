<?php
/**  
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package Editor 
 */  

namespace Soul\Cms;

use Phalcon\Forms\Element\TextArea;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Soul\Form\CmsEditorForm;
use Soul\Module;


/**
 * Class Editor
 *
 * @package Soul\Cms
 */
class Editor extends Module
{
    protected $file;

    protected $editor = '';

    protected $controller = null;


    public function __construct(Controller $controller)
    {
        $this->editor = new CmsEditorForm();
        $this->controller = $controller;

        $this->file = $this->controller->view->getActiveRenderPath();

        if (!is_writeable($this->file)) {
            throw new \Exception(sprintf('View file %s is not readable!', $this->file));
        }
    }

    /**
     *
     */
    public function edit()
    {
        $this->controller->view->setRenderLevel(View::LEVEL_LAYOUT);

        $this->editor->get('content')->setDefault(file_get_contents($this->file));

        echo $this->editor->render('cms');
    }

    /**
     *
     */
    public function save()
    {
        if ($this->controller->request->isPost()) {
            if ($this->editor->isValid($this->controller->request->getPost())) {
                die (var_dump($this->controller->request->get('content')));
            }
        }
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
} 