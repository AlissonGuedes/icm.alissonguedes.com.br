<?php

/**
 * Carrega as visões via ajax()
 */
function load_view($view)
{

	if (isAjax())
	{
		echo json_encode($view);
	}
	else
	{
		echo $view;
	}

}
