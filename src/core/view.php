<?php
namespace nqs;

class view {

    private $view;

    public function __construct($view)
    {
        
        $this->view = $view;

        if(!isset($this->view['view']))
           return;

        if (isset($view['matches']))
        {
            for ($i = 0; $i < sizeof($view['matches']); $i++) 
                database::add([ "parm_". $i => $view['matches'][$i]]);
        }
        else
        {
            database::add([ "parm_1" => $this->view['view']]);
        }

        if(isset($view['databases']))
            for ($i = 0; $i < sizeof($view); $i++)
                database::load($view['databases'][$i]);
    
    }

    public function render()
    {
        
        if(isset($this->view['url']))
        {
            header('Location: ' . $this->view['url']);
            return;
        }

        echo render::render($this->view['view'], database::$data);
        return;

    }

}
