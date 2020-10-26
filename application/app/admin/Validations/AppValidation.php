<?php

namespace App\Validations {

	class AppValidation {

		protected $allowedFields = [];

		public function getAllowedFields() {
			return $this -> allowedFields;
		}

	}

}