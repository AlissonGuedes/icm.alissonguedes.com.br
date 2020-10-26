<?php

namespace App\Controllers {

	use \App\Models\SiteModel;

	class Configuracoes extends AppController {

		//--------------------------------------------------------------------

		public function __construct() {

			$this -> site_model = new SiteModel();

		}

		//--------------------------------------------------------------------

		public function globais() {

			$dados = [];

			return $this -> view('configuracoes/globais/index', $dados);

		}

		//--------------------------------------------------------------------

		public function form($id) {

			$site = [];

			if ( is_numeric($id) )
				$site = $this -> site_model -> getAll($id);
			else 
				$site= ['status' => '1', 'bloqueado' => '0'];

			echo json_encode($site);

		}

		//--------------------------------------------------------------------

		public function show($id) {

			$dados['users'] = $this -> site_model -> getAll();
			$dados['row'] = $this -> site_model -> getUsers($id);

			return $this -> view('site/index', $dados);

		} 
		
		//--------------------------------------------------------------------

		public function insert() {

			$type   = null;
			$status = null;
			$msg	= null;
			$fields = null;

			if ( $this -> site_model -> create() ) {
				$msg = 'Usuário adicionado com sucesso!';
				$status = 'success';
				$type = 'back';
			} else {
				$status = 'error';
				$fields = $this -> site_model -> errors();
			}

			echo json_encode(['status'=> $status, 'message' => $msg, 'fields' => $fields, 'type' => $type]);

		}

		//--------------------------------------------------------------------

		public function update($id) {

			$type   = null;
			$status = null;
			$msg	= null;
			$fields = null;

			if ( $this -> site_model -> edit() ) {
				$msg = 'Dados atualizados com sucesso!';
				$status = 'success';
			} else {
				$status = 'error';
				$fields = $this -> site_model -> errors();
			}

			echo json_encode([ 'status'=> $status, 'message' => $msg, 'fields' => $fields, 'type' => $type ]);

		}

		//--------------------------------------------------------------------

		public function delete() {

			if ( $this -> site_model -> remove() );
				$msg = 'Usuário removido.';
			
			echo json_encode(['status'=> 'success', 'message' => $msg]);

		}

		//--------------------------------------------------------------------

	}

}