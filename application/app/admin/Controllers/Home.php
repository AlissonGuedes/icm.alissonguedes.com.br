<?php

namespace App\Controllers {
	
	class Home extends AppController
	{

		//--------------------------------------------------------------------

		public function index() {

			$dados['usuario'] = 'teste';

			return $this -> view('welcome_message', $dados);

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