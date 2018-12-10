<?php

class Model
{
	// метод выборки данных
	public function get_data()
	{
		// todo
	}

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

    protected function getSession()
    {
        require_once 'application/core/session.php';
        return new Session();
    }

    protected function redirect($route)
    {
        header('Location: ' . $route);
        return null;
    }
}
