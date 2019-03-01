<?php
class Route
{
    private $_uri = array();
    private $_method = array();

    public function add($uri, $method = null)
    {
        $this->_uri[] = $uri;
        $this->_method[] = $method;
    }
    public function submit()
    {
        $uriGetParam = isset($_GET['uri']) ? $_GET['uri'] : '/';
        foreach($this->_uri as $key => $value)
        {
            if(preg_match("#^$value$#", $uriGetParam))
                new $this->_method[$key]();
        } 
    }
}
?>