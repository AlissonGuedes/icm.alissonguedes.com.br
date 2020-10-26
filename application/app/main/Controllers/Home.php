<?php

namespace App\Controllers {

	use \App\Models\ListaModel;

	class Home extends AppController
	{


		public function __construct() {

			$this -> lista_oracao = new ListaModel();

		}
		
		//--------------------------------------------------------------------

		public function index() {

			$dados = [];

			$dados['igrejas'] = $this -> lista_oracao -> getIgreja() -> result();
			$dados['horario'] = $this -> lista_oracao -> getHorario() -> result();
			$dados['membros'] = $this -> lista_oracao -> getLista() -> result();
			$dados['horarios'] = $this -> lista_oracao -> getHorariosDisponiveis() -> result();
			return $this -> view('home', $dados);

		}

		public function salvar() {

			$type   = null;
			$status = null;
			$msg	= null;
			$fields = null;

			if ( $this -> lista_oracao -> create() ) {
				$msg = 'Horário adicionado com sucesso!';
				$status = 'success';
				$type = 'refresh';
			} else {
				$status = 'error';
				$fields = $this -> lista_oracao -> errors();
			}

			echo json_encode(['status'=> $status, 'message' => $msg, 'fields' => $fields, 'type' => $type]);

		}



		//--------------------------------------------------------------------

		/**
		 * 
		 * !!!NAO EXCLUIR ISSO ATÉ FIXAR COMO SERÁ PROGRAMADO O SISTEMA!!!
		 * 
		 * Apenas exemplo para saber como serão realizadas as consultas dos dados
		 * para envio destes para os templates 
		 */
		// public function vw_formulario() {

		// 	$dados = ['nome' => 'alisson', 'id' => '1'];

		// 	$dados['grupos'] = [
		// 		['id' => '1', 'grupo' => 'Grupo 1', 'selected' => false],
		// 		[ 'id' => '2', 'grupo' => 'Grupo 2', 'selected' => false],
		// 		[ 'id' => '3', 'grupo' => 'Grupo 3', 'selected' => false],
		// 		[ 'id' => '4', 'grupo' => 'Grupo 4', 'selected' => 'selected="selected"'],
		// 		[ 'id' => '5', 'grupo' => 'Grupo 5', 'selected' => false],
		// 		[ 'id' => '6', 'grupo' => 'Grupo 6', 'selected' => false]
		// 	];

		// 	$dados['usuario'] = $this -> template('usuarios/formulario', $dados);

		// 	return $this -> view('welcome_message', $dados); 

		// }
		
		//--------------------------------------------------------------------
		
	}
	
}
