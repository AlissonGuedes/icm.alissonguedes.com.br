<?php

namespace App\Models {

	use \CodeIgniter\Model;

	class SiteModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser utilizada
		 * 
		 * @var string $table
		 */
		protected $table = 'tb_sys_site';

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
		protected $returnType = '\App\Entities\Site';

		/**
		 * Validação para formulários
		 * 
		 * @var array $validationClass
		 */
		protected $validationClass = '\App\Validations\SiteValidation';
		
		/**
		 * Especificar quais colunas da tabela serão ordenadas
		 * 
		 * @var array $order
		 */
		protected $order = [
			null, 
			'tb_sys_site.nome',
			'(SELECT grupo FROM tb_acl_grupo WHERE tb_acl_grupo.id = tb_sys_site.id_grupo)',
			'tb_sys_site.email',
			'tb_sys_site.ultimo_login',
			'tb_sys_site.status'
		];

		//--------------------------------------------------------------------

		public function getAll($find = null) {

			// $this -> select('tb_sys_site.id, tb_sys_site.id_grupo AS grupo, tb_sys_site.nome, tb_sys_site.email, tb_sys_site.login, tb_sys_site.ultimo_login, tb_sys_site.status, tb_sys_site.bloqueado');

			// $this -> join('tb_acl_grupo', 'tb_sys_site.id_grupo = tb_acl_grupo.id', 'left');

			if ( defined('USERDATA') && isset($_SESSION[USERDATA]))
				$this -> where('tb_sys_site.id <> ', $_SESSION[USERDATA]['id']);

			if ( !is_null($find) && is_numeric($find) ) {
				$this -> where('tb_sys_site.id', $find);
				return $this -> get() -> getRow();
			}

			if ( isset($_GET['search']['value']) && ! empty($_GET['search']['value'])) {

				$search = $_GET['search']['value'];

				$this -> groupStart();

				$this -> orLike('tb_sys_site.id', $search);
				$this -> orLike('tb_sys_site.nome', $search);
				$this -> orLike('tb_sys_site.email', $search);
				$this -> orLike('tb_sys_site.login', $search);
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

			// echo $this -> getCompiledSelect();
			return $this;

		}

	}

}