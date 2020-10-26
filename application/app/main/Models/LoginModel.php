<?php

namespace App\Models {

	/**
	 * @className UserModel
	 * @package App
	 */
	class LoginModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser utilizada
		 * 
		 * @var string $table
		 */
		protected $table = 'tb_acl_usuario';

		/**
		 * A chave primária da tabela
		 * 
		 * @var string $primaryKey
		 */
		protected $primaryKey = 'id';

		/**
		 * Classe espelho do banco de dados
		 * 
		 * @var string $returnType
		 */
		protected $returnType = 'App\Entities\User';

		/**
		 * Validação para formulários
		 * 
		 * @var array $formValidation
		 */
		protected $validationClass = 'App\Validations\LoginValidation';

		/**
		 * Especificar quais colunas da tabela serão ordenadas
		 * 
		 * @var array $order
		 */
		protected $order = array();

		public function __construct() {

			parent :: __construct();
			$this -> user = $this -> entity;

		}

		private function login_verify() {
			
			// Primeiramente, precisamos verificar se o que o usuário envia é um login ou senha.
			if ( isset($_POST['login']) ) {
	
				$login = $_POST['login'];
				
				// Depois que o usuário enviar o login, iremos verificar se este login existe e
				// está ativo no banco de dados.
				$columns = [ 'login', 'email' ];

				$this -> select($columns)
						-> groupStart()
						-> where('login', $login)
						-> orWhere('email', $login)
						-> groupEnd()
						-> where('status', '1');

				$user = $this -> get() -> getRowArray();

				// Se o login existir e estiver ativo, vamos criar um cookie para este login.
				if ( isset($user) ) {

					// Aqui criamos o cookie para utilizá-lo quando o usuário enviar a senha.
					$data 			= [USERDATA => $user];

					$this -> session -> set($data);

					return true;

				}
				
				// Se não existir o usuário...
				else {

					// Vamos limpar os dados de sessão e retornar a mensagem de usuário inexistente.
					$this -> session -> remove(USERDATA);
					$this -> error('login', __INVALID_USER__);

					// parar por aqui.
					return false;

				}

			}

		}

		private function pass_verify() {

			if ( isset($_POST['senha']) ) {

				$password = hashCode($_POST['senha']);

				$columns = [
					'id', 'id_grupo','nome', 'Img.imagem', 'email', 
					'senha', 'ultimo_login', 'login', 'permissao'
				];

				$this -> select($columns)
					  -> join('tb_acl_usuario_imagem Img', 'Img.id_usuario = tb_acl_usuario.id', 'left')
					  -> where('login', $_SESSION[USERDATA]['login'])
					  -> where('senha', $password)
					  -> where('status', '1');

				$user = $this -> get() -> getRowArray();

				if ( isset($user) ) {

					$user['passthru'] =	true;
					$user['token']	  = USERDATA . time();
					$data 			  = [USERDATA => $user];
					$this -> session -> set($data);

					return true;

				} else {

					$this -> error('senha', __INVALID_PASSWORD__);

					// parar por aqui.
					return false;

				}

			}

		}

		public function userAuthentication() {

			if ( $this -> validate($_POST) === FALSE ) 
				return false;

			if ( $this -> login_verify() ) {
				return true;
			}
	
			if ( $this -> pass_verify() ) {
				return true;
			}
			
			return false;

		}

	}

}
