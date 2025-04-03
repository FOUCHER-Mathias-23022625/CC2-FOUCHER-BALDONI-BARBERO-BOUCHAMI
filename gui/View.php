<?php
namespace gui;

abstract class View
{
    protected $title = '';
    protected $content = '';
    protected $layout;

    /**
     * @param $layout
     */
    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return void
     */
    public function display()
    {
        $this->layout->display( $this->title, "", $this->content );
    }
}