<?php

namespace App\Models {

	/**
	 * @className UserModel
	 * @package App
	 */
	class AclGrupoModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser utilizada
		 * 
		 * @var string $table
		 */
		protected $table = 'tb_acl_grupo G';

		/**
		 * A chave primária da tabela
		 * 
		 * @var string $primaryKey
		 */
		protected $primaryKey = 'g_id';

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
		protected $validationClass = 'App\Validations\UserValidation';

		/**
		 * Especificar quais colunas da tabela serão ordenadas
		 * 
		 * @var array $order
		 */
		protected $order = [
			null,
			'grupo',
			'descricao',
			'status'
		];

		public function __construct() {

			parent :: __construct();
			$this -> user = $this -> entity;

		}

		public function getAll($find = null){

			$this -> select('G.id AS gid, G.grupo, G.descricao, G.status');

			if ( !is_null($find) && is_numeric($find) ) {
				$this -> where('id', $find);
				return $this -> get() -> getRowObject();
			}

			// Limit
			if ( isset($_GET['length']) )
				$this -> limit($_GET['length']);

			// Offset
			if ( isset($_GET['start']) )
				$this -> offset($_GET['start']);

			// Order By
            if (isset($_GET['order']) && $_GET['order'][0]['column'] != 0 ) {
                $orderBy = $this -> order[$_GET['order'][0]['column']];
				$direction = $_GET['order'][0]['dir'];
			} else {
				$orderBy = $this -> order[1];
				$direction = 'asc';
			}

			$this -> orderBy($orderBy, $direction);

			// Like
			if ( isset($_GET['search']) )
				$this -> orLike('grupo', $_GET['search']['value']);

			return $this;

		}

		public function getById($id) {

			$this -> select('U.id, U.id_grupo AS grupo, U.nome, U.login, U.email, U.status, U.bloqueado');
			$this -> where('U.id', $id);
			return $this -> get() -> getRowArray();

		}

	}

}