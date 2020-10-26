<?php

namespace App\Models {

	use \CodeIgniter\Model;

	class UsuarioModel extends AppModel {

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
		protected $returnType = '\App\Entities\User';

		/**
		 * Validação para formulários
		 * 
		 * @var array $validationClass
		 */
		protected $validationClass = '\App\Validations\UserValidation';
		
		/**
		 * Especificar quais colunas da tabela serão ordenadas
		 * 
		 * @var array $order
		 */
		protected $order = [
			null,
			'tb_acl_usuario.nome',
			'(SELECT grupo FROM tb_acl_grupo WHERE tb_acl_grupo.id = tb_acl_usuario.id_grupo)',
			'tb_acl_usuario.email',
			'tb_acl_usuario.ultimo_login',
			'tb_acl_usuario.status'
		];

		//--------------------------------------------------------------------

		public function getAll($find = null) {

			$this -> select('tb_acl_usuario.id, tb_acl_usuario.id_grupo, tb_acl_usuario.nome, tb_acl_usuario.email, tb_acl_usuario.login, tb_acl_usuario.ultimo_login, tb_acl_usuario.status');

			$this -> join('tb_acl_grupo', 'tb_acl_usuario.id_grupo = tb_acl_grupo.id', 'left');
			
			if ( !is_null($find) ) {
				$this -> where('tb_acl_usuario.id', $find);
				$this -> orWhere('tb_acl_usuario.login', $find);
				$this -> orWhere('tb_acl_usuario.email', $find);
				return $this ;
			}

			if ( defined('USERDATA') && isset($_SESSION[USERDATA]))
				$this -> where('tb_acl_usuario.id <> ', $_SESSION[USERDATA]['id']);
			
			if ( isset($_GET['search']['value']) && ! empty($_GET['search']['value'])) {

				$search = $_GET['search']['value'];

				$this -> groupStart();

				$this -> orLike('tb_acl_usuario.id', $search);
				$this -> orLike('tb_acl_usuario.nome', $search);
				$this -> orLike('tb_acl_usuario.email', $search);
				$this -> orLike('tb_acl_usuario.login', $search);
				$this -> orLike('tb_acl_grupo.grupo', $search);

				$this -> groupEnd();
				
			}

			if ( isset($_GET['start']) ) {
				$this -> limit($_GET['length']);
			}

			if ( isset($_GET['length']) ) {
				$this -> offset($_GET['start']);
			}

			// Order By
            if (isset($_GET['order']) && $_GET['order'][0]['column'] != 0 ) {
                $orderBy = $this -> order[$_GET['order'][0]['column']];
				$direction = $_GET['order'][0]['dir'];
			} else {
				$orderBy = $this -> order[1];
				$direction = 'asc';
			}

			$this -> orderBy($orderBy, $direction);

			return $this;

		}

		public function updateResource($resource) {
			
			$msg = null;
			$column = $resource;
			$id = $_POST['id'];
			$value = $_POST['value'];

			$this -> builder() -> set($column, $value) -> whereIn('id', $id) -> update();

			switch($resource) {
				case 'status':
					if ( $value ) 
						$msg = count($id) . ' ' . (count($id) > 1 ? 'usuários desbloqueados' : 'usuário desbloqueado') . '.';
					else
						$msg = count($id) . ' ' . (count($id) > 1 ? 'usuários bloqueados' : 'usuário bloqueado') . '.';
				break;
			}

			return $msg;

		}

	}

}
