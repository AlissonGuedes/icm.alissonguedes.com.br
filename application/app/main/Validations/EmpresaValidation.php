<?php 

namespace App\Validations {

	class EmpresaValidation extends AppValidation {

		public function getAllowedFields() {

			$this -> allowedFields =  [
				'nome',
				'id_grupo',
				'email',
				'login',
				( ! empty($_POST['senha']) ? 'senha' : NULL ),
				'status'
			];

			return $this -> allowedFields;

		}

		public function getRules() {

			$validate['nome'] = ['trim', 'required'];
			$validate['login'] = array('trim', 'required', 'is_unique[tb_usuario.login,id,{id}]');
			$validate['email'] = array('trim', 'required', 'is_unique[tb_usuario.email,id,{id}]');
			
			return $validate;

		}

	}

}