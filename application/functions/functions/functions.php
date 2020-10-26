<?php

/**
 * Esta função processa uma matriz de alertas para orientar o usuário sobre as
 * ações do sistema.
 *
 * @var title: string
 * @var $type: error|info|sucess|warning
 * @var $title: string
 * @var $msg: string
 * @var fields: array
 * @var $url: string
 * @var $redirect: string
 *      reload: Atualiza a página após um retorno do TIPO sucesso
 *      refresh: Atualiza dataTable após um retorno do TIPO sucesso
 *      redirect: Redireciona a página após um retorno do TIPO sucesso
 *      bool = TRUE|FALSE
 */
if ( ! function_exists('alert') )
{
	function alert($params = array())
	{

		$message = null;

		$title = ! empty($params['title']) ? $params['title'] : null;
		$type = ! empty($params['type']) ? $params['type'] : 'error';
		$datatype = ! empty($params['datatype']) ? $params['datatype'] : 'json';
		$action = ! empty($params['action']) ? $params['action'] : null;
		$msg = ! empty($params['msg']) ? $params['msg'] : null;
		$fields = ! empty($params['fields']) ? $params['fields'] : array();
		$url = ! empty($params['url']) ? $params['url'] : null;
		$style = ! empty($params['style']) ? $params['style'] : null;

		$redirect = (isset($params['redirect']) && ( ! empty($params['redirect']) || $params['redirect'] === false)) ? $params['redirect'] : NULL;

		if ( is_array($msg) )
		{
			foreach ( $msg as $ind => $val )
			{
				if ( is_array($val) )
				{
					foreach ( $val as $v )
					{
						$message[] = array($ind => $v);
					}
				}
				else
				{
					$message = $val;
				}
			}
		}
		else
		{
			$message = $msg;
		}

		$encode = array(
			'title' => $title,
			'type' => $type,
			'datatype' => $datatype,
			'action' => $action,
			'msg' => $message,
			'fields' => $fields,
			'url' => $url,
			'redirect' => $redirect,
			'style' => $style
		);

		echo json_encode($encode);

	}

}

/**
 * Acessar todas as variáveis públicas de uma classe
 */
if ( ! function_exists('get_vars') )
{

	function get_vars($class, $directory = 'entity')
	{

		$vars = array();

		$CI = &get_instance();
		$cl = load_class($class, $directory);

		$class_vars = get_class_vars(get_class($cl));

		foreach ( $class_vars as $name => $value )
		{
			$var = $name;
			$ind[] = $name;
			$val[] = $cl -> $var;
		}

		if ( ! empty($ind) && ! empty($val) && count($ind) == count($val) )
		{
			$vars = array_combine($ind, $val);
		}

		return $vars;
	}

}

/**
 * Gravar log na base de dados.
 */
if ( ! function_exists('grava_log') )
{

	/**
	 * Error Logging Interface
	 *
	 * We use this as a simple mechanism to access the logging
	 * class and send messages to be logged.
	 *
	 * @param   string  the error level: 'error', 'debug' or 'info'
	 * @param   string  the error message
	 * @return  void
	 */
	function grava_log($level, $message, $tipo = NULL)
	{

		$CI = DB('admin');

		$CI -> db -> set('datahora', date('Y-m-d H:i:s'));
		$CI -> db -> set('ip', getIpAddress());
		$CI -> db -> set('tipo', $tipo);
		$CI -> db -> set('nivel', $level);
		$CI -> db -> set('mensagem', $message);

		$CI -> db -> insert('tb_log');

		log_message($level, $message);

	}

}

/**
 * Permissão de usuários
 */
if ( ! function_exists('get_permissoes') )
{

	function get_permissoes($grupo = NULL, $controle = NULL, $tipo = 'listar')
	{

		return true;

		$CI = DB();

		$CI -> select('
            P.id, P.id_controle, P.id_grupo, P.listar, P.inserir, P.editar, P.remover, P.status p_status,
            M.id, M.diretorio, M.status m_status,
            C.id, C.id_menu, C.controller, C.route,
            G.id, G.status g_status,
            GM.id, GM.id_grupo, GM.id_modulo
        ');

		$CI -> from('tb_acl_permissao P');
		$CI -> join('tb_acl_controle C', 'P.id_controle = C.id', 'left');
		$CI -> join('tb_acl_grupo G', 'P.id_grupo = G.id', 'left');
		$CI -> join('tb_acl_grupo_modulo GM', 'GM.id_grupo = G.id', 'left');
		$CI -> join('tb_modulo M', 'M.id = GM.id_modulo', 'left');

		$CI -> where('G.id = ', $grupo['id_grupo']);

		$CI -> where('M.diretorio', basename(APPPATH));

		if ( $controle != NULL )
			$CI -> where('C.controller', $controle);

		// if ( $tipo != 'listar' )
		// {
		// $CI -> group_start();
		// $CI -> where('P.listar', 'S');
		// $CI -> where('P.inserir', 'S');
		// $CI -> where('P.editar', 'S');
		// $CI -> or_where('P.remover', 'S');
		// $CI -> group_end();
		// }

		$CI -> where('G.status', '1');
		$CI -> where('P.status', '1');
		$CI -> where('M.status', '1');

		// echo $CI  -> get_compiled_select();

		$query = $CI -> get();

		if ( $controle != NULL )
		{
			$permission = $query -> row();

			if ( (isset($permission) && $permission -> $tipo === 'S' && $permission -> p_status === '1' && $permission -> g_status) )
				return true;
			else
				return false;
		}

		return $query;

	}

}

if ( ! function_exists('verifica_login') )
{

	function verifica_login()
	{

		if ( isset_login_file() && ! is_logged() )
		{
			// echo '<script>alert("Login expirado... entre novamente"); location.href = "' .
			// base_url() . 'login";</script>';
			header('Location: ' . base_url() . 'login$1');
			// exit();

		}

	}

}

if ( ! function_exists('login') )
{

}

if ( ! function_exists('logout') )
{

	function logout()
	{

		// $CI = &get_instance();
		// $CI -> load -> library('Session');
		//
		// if ( isset($_SESSION[USERDATA]) )
		// {
		// unset($_SESSION[USERDATA]);
		// }
		// else
		// {
		// foreach ( $_COOKIE as $ind => $val )
		// {
		// setcookie($ind, '');
		// }
		// }
		//
		// header('Location: ' . base_url());

	}

}
