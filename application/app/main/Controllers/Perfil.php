<?php

namespace App\Controllers {

	use \App\Models\UsuarioModel;
	use \App\Models\AclGrupoModel;

	class Perfil extends AppController {

		//--------------------------------------------------------------------

		public function __construct() {

			$this -> usuario_model = new UsuarioModel();
			$this -> grupo_model   = new AclGrupoModel();

		}

		//--------------------------------------------------------------------

		public function index() {

			if ( isAjax() )
			{

				$dados['usuarios'] = $this -> usuario_model -> getAll();
				$dados['numRows']  = $this -> usuario_model -> numRows();
				return $this -> view('configuracoes/usuarios/datatable', $dados);

			}

			$dados['grupo'] = $this -> grupo_model -> getAll();
			return $this -> view('configuracoes/usuarios/index', $dados);

		}

		//--------------------------------------------------------------------

		public function perfil($id) {

			$dados = [];
			$dados['usuario'] = $this -> usuario_model -> getAll($_SESSION[USERDATA]['id']) -> get() -> getRow();

			if( !isAjax() ) {
				return $this -> view('perfil/index', $dados);
			}

			echo json_encode($usuario);

		}

		//--------------------------------------------------------------------

		public function form($id) {

			$usuario = [];

			if ( is_numeric($id) )
				$usuario = $this -> usuario_model -> getAll($id);
			else 
				$usuario = ['status' => '1', 'bloqueado' => '0'];

			echo json_encode($usuario);

		}

		//--------------------------------------------------------------------

		public function show($id) {

			$dados['users'] = $this -> usuario_model -> getAll();
			$dados['row'] = $this -> usuario_model -> getUsers($id);

			return $this -> view('configuracoes/usuarios/index', $dados);

		}
		
		//--------------------------------------------------------------------

		public function insert() {

			$type   = null;
			$status = null;
			$msg	= null;
			$fields = null;

			if ( $this -> usuario_model -> create() ) {
				$msg = 'Usuário adicionado com sucesso!';
				$status = 'success';
				$type = 'back';
			} else {
				$status = 'error';
				$fields = $this -> usuario_model -> errors();
			}

			echo json_encode(['status'=> $status, 'message' => $msg, 'fields' => $fields, 'type' => $type]);

		}

		//--------------------------------------------------------------------

		public function update() {

			$type   = null;
			$status = null;
			$msg	= null;
			$fields = null;

			if ( $this -> usuario_model -> edit() ) {
				$msg = 'Usuário atualizado com sucesso!';
				$status = 'success';
			} else {
				$status = 'error';
				$fields = $this -> usuario_model -> errors();
			}

			echo json_encode([ 'status'=> $status, 'message' => $msg, 'fields' => $fields, 'type' => $type ]);

		}

		//--------------------------------------------------------------------

		/**
		 * @param $resource => Nome do recurso a ser alterado.
		 */
		public function replace($resource) {

			$type   = null;
			$status = null;
			$msg	= null;
			$fields = null;

			if ( $msg = $this -> usuario_model -> updateResource($resource) ) {
				$status = 'success';
			} else {
				$status = 'error';
				$fields = $this -> usuario_model -> errors();
			}

			echo json_encode([ 'status'=> $status, 'message' => $msg, 'fields' => $fields, 'type' => $type ]);

		}

		//--------------------------------------------------------------------

		public function delete() {

			if ( $msg = $this -> usuario_model -> remove() )
				$status = 'success';
			else
				$status = 'error';

			echo json_encode(['status'=> 'success', 'message' => $msg]);

		}

		//--------------------------------------------------------------------

	}

}