<?php

class View
{

	//public $template_view; // здесь можно указать общий вид по умолчанию.

	/*
	$content_file - виды отображающие контент страниц;
	$template_file - общий для всех страниц шаблон;
	$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	*/
	function generate($content_view, $menu_view, $template_view, $data = null, $secondaryData = null)
	{

		/*
		if(is_array($data)) {

			// преобразуем элементы массива в переменные
			extract($data);
		}
		*/

		/*
		динамически подключаем общий шаблон (вид),
		внутри которого будет встраиваться вид
		для отображения контента конкретной страницы.
		*/
		include 'application/views/'.$template_view;
	}

    protected function getCss($css)
    {
        echo "<link rel='stylesheet' type='text/css' href='/css/" . $css . ".css'>";
    }

    protected function getJS($js)
    {
        echo "<script src='" . $js .".js' type='text/javascript' ></script>";
    }

    protected function getSession()
    {
        require_once 'application/core/session.php';
        return new Session();
    }

    public function displayErrors()
    {
        $session = $this->getSession();
        if($session->get('errors')) {
            $errors = $session->get('errors');
            if (is_array($errors)) {
                foreach($errors as $error) {
                    echo $error . '<br>';
                }
            } else {
                echo $errors;
            }
            $session->unsetValue('errors');
        }
        return null;
    }

    public function getWysiwygResource()
    {
        echo '<script src="/js/wysiwyg/ckeditor.js"></script>';
        echo '<script src="/js/wysiwyg/google.api.ajax.js"></script>';
        echo '<script src="/js/wysiwyg/adapters/jquery.js"></script>';
        echo '<script> $(document).ready(function() {
	$("#editor1").ckeditor();
} );</script>';
    }
}
