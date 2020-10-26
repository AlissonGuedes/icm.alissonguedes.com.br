<?php 

namespace App\Validations {

	class LoginValidation extends AppValidation {

		public function getAllowedFields() {

			$this -> allowedFields =  [
				'primeiro_login',
				'ultimo_login'
			];

			return $this -> allowedFields;

		}

		public function getRules() {

			$validate['login'] = array('trim', 'required');
			$validate['senha'] = array('trim', 'required');

			return $validate;

		}

	}

}