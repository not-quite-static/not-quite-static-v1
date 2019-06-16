<?php
namespace nqs;

class view {


    private $view;

    public function __construct($view)
    {
        
        $this->view = $view;

        if (isset($view['matches']))
        for ($i = 0; $i < sizeof($view['matches']); $i++) 
            database::add([ "parm_". $i => $view['matches'][$i]]);
    
        if(isset($view['databases']))
            for ($i = 0; $i < sizeof($view); $i++)
                database::load($view['databases'][$i]);
    
    

    }

    public function render()
    {
        
        echo render::render($this->view['view'], database::$data);
        return;

    }

}
