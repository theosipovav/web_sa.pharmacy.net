<?php class Router 
{
    var $controller;
    var $view;
    var $param;

    public function __construct() {
        $controller = "main";
        $view = "index";
        $param = array();
        if (isset($_GET["r"]))
        {
            if (strlen($_GET["r"]) > 0) 
        {
            $a_get = explode("/", $_GET["r"]);
            for ($i=0; $i < count($a_get); $i++)
            {
                if ($i == 0 ) $controller = $a_get[0];
                if ($i == 1 ) $view = $a_get[1];
                if ($i > 1)
                {
                    array_push($param, $a_get[$i]);
                }
                
            }
        }
        }
        $this->controller = $controller;
        $this->view = $view;
        $this->param = $param;
    }

    public function __destruct()
    {

    }

    public function Render() 
    {
        echo "c: " . $this->controller . "<hr>";
        echo "v: " . $this->view . "<hr>";
        echo "p: " . "<pre>".print_r($this->param,true)."</pre>";

        $qwe = new app\controllers\main;
    }
}