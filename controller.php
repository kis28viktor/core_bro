<?php

/**
 * Class Controller
 */
class Controller {

    public $session;

	public $model;
    /**
     * @var View
     */
	public $view;

	function __construct()
	{
		$this->view = new View();
	}

	// действие (action), вызываемое по умолчанию
	function action_index()
	{
		// todo
	}

    /**
     * @param $modelName
     * @return
     */
    protected function getModel($modelName)
    {
        $modelName = strtolower($modelName);
        require_once 'application/models/' . $modelName . '.php';
        $last = strrpos($modelName, '/');
        if($last) {
            $model = substr($modelName, $last+1);
        } else {
            $model = $modelName;
        }
        return new $model();
    }

    protected function getGetParam($param)
    {
        if(isset($_GET[$param])) {
            return $_GET[$param];
        } else {
            return null;
        }
    }

    protected function setGetParam($key, $value)
    {
        $_GET[$key] = $value;
        return null;
    }

    protected function setPostParam($key, $val)
    {
        $_POST[$key] = $val;
        return null;
    }

    protected function getPostParam($param)
    {
        if (isset($_POST[$param])) {
            return $_POST[$param];
        } else {
            return null;
        }
    }

    protected function getSession()
    {
        if ($this->session==null) {
            require_once 'application/core/session.php';
            $this->session =  new Session();
        }
        return $this->session;
    }

    protected function redirect($route)
    {
        header('Location: ' . $route);
        return null;
    }
}
