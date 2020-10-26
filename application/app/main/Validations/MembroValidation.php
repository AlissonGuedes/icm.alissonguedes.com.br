<?php 

namespace App\Validations {

	class MembroValidation extends AppValidation {

		public function getAllowedFields() {

			$this -> allowedFields =  [
				'id_igreja',
				'nome',
				'email',
				'telefone',
				'horario'
			];

			return $this -> allowedFields;

		}

		public function getRules() {

			$validate = [];

			$validate = [
				'nome' => [
					'rules' => ['trim', 'required'],
					'errors' => [
						'required' => 'Informe o seu nome.'
					]
				],
				'horario' => [
					'rules' => 'trim',
					'errors' => [
						'required' => 'Informe um horário'
					]
				]
			];

			if ( empty($_POST['igreja']) )
				$validate['igreja'] = [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'Informe a sua igreja'
					]
				];

			if ( empty($_POST['horario']))
				$validate['horario'] = [
					'rules' => 'trim|required',
					'errors' => [
						'required' => 'Informe um horário'
					]
				];

			return $validate;

		}

	}

}