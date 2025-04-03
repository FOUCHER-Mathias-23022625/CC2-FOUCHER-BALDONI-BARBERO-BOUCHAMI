<?php
namespace gui;
class Layout
{
    protected $templateFile;

    /**
     * @param $templateFile
     */
    public function __construct( $templateFile )
    {
        $this->templateFile = $templateFile;
    }

    /**
     * @param $title
     * @param $connexion
     * @param $content
     * @return void
     */
    public function display( $title, $connexion, $content )
    {
        $page = file_get_contents( $this->templateFile );
        $page = str_replace( ['%title%','%connexion%','%content%'], [$title, $connexion, $content], $page);
        echo $page;
    }

}