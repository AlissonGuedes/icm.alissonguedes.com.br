<?php

namespace App\Models {

	use \CodeIgniter\Model;

	class ListaModel extends AppModel {

		/**
		 * Nome da tabela do banco de dados a ser utilizada
		 * 
		 * @var string $table
		 */
		protected $table = 'tb_membro';

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
		protected $returnType = '\App\Entities\Membro';

		/**
		 * Validação para formulários
		 * 
		 * @var array $validationClass
		 */
		protected $validationClass = '\App\Validations\MembroValidation';
		
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

		public function getIgreja($find = null) {

			$this -> from('tb_igreja', true);

			$this -> select('id, igreja, bairro');

			return $this;

		}

		private $horario;

		public function getHorario($horario = null) {

			$this -> horario = $horario;
			$this -> from('tb_horario', true);

			$this -> select('id, horario', false);

			return $this;

		}

		public function getMembroHorario($horario = null) {

			$this -> from('tb_membro', true);

			$this -> select('id, horario', false);

			$this -> where('horario', $horario);

			return $this;

        }

		public function getLista(){

			$this -> from('tb_membro M', true);

			$this -> select('M.nome, M.horario, I.igreja', false);
			$this -> join('tb_igreja I', 'I.id = M.id_igreja', 'left');
			$this -> orderBy('M.horario ASC');

			return $this;

		}

		public function getHorariosDisponiveis(){

			$this -> from('tb_horario H', true);

			$this -> whereNotIn('H.horario', function($builder) {
				return $builder -> select('M.horario', false) -> from('tb_membro M') -> where('M.horario = H.horario');
			});
			$this -> orderBy('H.horario ASC');

			return $this;

		}
	}

}
