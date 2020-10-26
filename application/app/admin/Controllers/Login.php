<?php

namespace App\Controllers {

	use  \App\Models\LoginModel;

	class Login extends AppController
	{

		//--------------------------------------------------------------------

		public function __construct() {
			
			$this -> login_model = new LoginModel();
		
		}

		//--------------------------------------------------------------------

		public function index() {

			$dados['method']			= 'post';
			$dados['site_title']		= configuracoes('title');
			$dados['login_template']	= $this -> template('login/templates/login.html', $dados);

			return $this -> view('login/index', $dados);

		}
		
		//--------------------------------------------------------------------
		
		public function auth() {

			$status		= 'error';
			$message	= null;
			$data		= null;
			$fields		= null;
			$text		= null;
			$url		= base_url() . 'dashboard';
			$type		= 'refresh';
			
			if ( $user = $this -> login_model -> userAuthentication() ) {
				
				$status		= 200;
				$message	= 'Usuário logado com sucesso';
				
				if ( isset($_SESSION[USERDATA]['passthru']) ) {
					$status		= 'success';
					$data['token'] = $_SESSION[USERDATA]['token'];
				}
				
			} else {
				
				$message   = 'Por favor, verifique os campos marcados.';
				$fields = $this -> login_model -> errors();
				
			}
			
			echo json_encode(
				array(
					'status'	=> $status,
					'message'	=> $message,
					'data'		=> $data,
					'fields'    => $fields,
					'url'		=> $url,
					'type'		=> $type
				)
			);
			
		}

		//--------------------------------------------------------------------
			
		public function logout()
		{
			
			if ( isset($_SESSION[USERDATA]['passthru']) ) {
				$this -> session -> remove(USERDATA);
			}
			
			return location(base_url());

		}

		//--------------------------------------------------------------------

	}
	
}
