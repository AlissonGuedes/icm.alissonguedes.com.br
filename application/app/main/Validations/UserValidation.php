<?php 

namespace App\Validations {

	class UserValidation extends AppValidation {

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
			$validate['id_grupo'] = ['trim', 'required'];
			$validate['login'] = array('trim', 'required', 'is_unique[tb_acl_usuario.login,id,{id}]');
			$validate['email'] = array('trim', 'required', 'is_unique[tb_acl_usuario.email,id,{id}]');
			
			return $validate;

		}

	}

}