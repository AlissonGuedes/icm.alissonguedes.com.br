<?php

namespace App\Models {

	use \CodeIgniter\Model;

	class AppModel extends Model {

		//--------------------------------------------------------------------

		public function __construct() {

			parent :: __construct();

			$this -> session = \Config\Services::session();
			$this -> request = \Config\Services::request();
			$this -> uri     = \Config\Services::uri(current_url());

			$this -> entity				= new $this -> returnType();
			$this -> formValidation		= new $this -> validationClass();

			$this -> allowedFields		= $this -> formValidation -> getAllowedFields();
			$this -> validationRules	= $this -> formValidation -> getRules();

		}

		//--------------------------------------------------------------------

		public function result() {

			return $this -> get() -> getResult();

		}

		public function getArray() {

			return $this -> get() -> getRowArray();

		}

		//--------------------------------------------------------------------

		/**
		 * @method totalRows
		 * Retorna o total de registros na tabela
		 */
		public function totalRows() {

			return $this -> resetQuery() -> getAll() -> countAll(false);

		}

		//--------------------------------------------------------------------

		public function numRows() {

			return $this -> resetQuery() -> getAll() -> countAllResults(false);

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna mensagem de validação personalizada do
		 * usuário em caso de erro
		 *
		 * @return int Model::countAllResults()
		 */
		public function error($field, string $message = null)
		{

			$this -> validation -> setError($field, $message);
			return $this -> validation -> getError();

		}

		//--------------------------------------------------------------------

		public function create($debug = false) {

			if ( $this -> validate($_POST) === false )
				return false; 

			$data = $this -> entity -> fill($_POST);

			if ( $debug )
				return $this -> getQuery('insert', $this -> entity);

			return $this -> set($data)
						 -> insert();

		}

		//--------------------------------------------------------------------

		public function edit($debug = 1) {

			if ( $this -> validate($_POST) === false )
				return false; 

			$data = $this -> entity -> fill($_POST);

			if ( $debug )
				return $this -> getCompiledUpdate($data, ['id' => $_POST['id']]);

			$id = $_POST['id'];

			return $this -> set($data)
						 -> where('id', $id)
						 -> update();

		}

		//--------------------------------------------------------------------

		public function remove($debug = false) {

			$id = $_POST['id'];

			if ( $debug )
				return $this -> getCompiledDelete($id);

			$this -> whereIn('id', $id);
			$this -> delete();
			
			if ( $this -> affectedRows() ) {
				$msg = count($id) . ' ' . (count($id) > 1 ? 'usuários removidos' : 'usuário removido') . '.';
			} else {
				$msg = 'Não foi possível remover todos os registros selecionado.';
			}

			return $msg;

		}

		//--------------------------------------------------------------------

		/**
		 * @name Get Query
		 *
		 * Este método simplesmente retorna a consulta SQL como uma
		 * string.
		 *
		 * @param	string	$query	=	select|insert|update|delete
		 * 			int		$id		=	cláusula where
		 *			mixed	$data	=	campos para alteração
		 * 			boolean	$reset	=	$reset	TRUE: redefinir valores QB
		 * 										FALSE: manter os valores QB
		 * @return string
		 */
		
		public function getCompiledUpdate($data = null, $where = null)
		{

			$builder = $this -> builder();

			if ( ! is_null($data) )
				$this -> builder -> set($data);

			if ( ! is_null($where) )
				$this -> builder -> where($where);

			exit( $this -> builder -> getCompiledUpdate() );

		}

		public function getCompiledInsert($data = null)
		{

			$builder = $this -> builder();

			if ( ! is_null($data) )
				$this -> builder -> set($data);

			exit( $this -> builder -> getCompiledInsert() );

		}

		public function getCompiledDelete($id)
		{

			$builder = $this -> builder();

			exit( $this -> builder -> whereIn('id', $id) -> getCompiledDelete() );

		}

		//--------------------------------------------------------------------

	}

}