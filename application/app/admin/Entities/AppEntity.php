<?php

namespace App\Entities {
	
	use \CodeIgniter\Entity;

	class AppEntity extends Entity {

		protected $config;
		protected $allowedFields = [];

		public function getAllowedFields() {
			return $this -> setAllowedFields();
		}

		public function fill($data = null)
		{
			
			if ( !$data )
				return FALSE;

			// Obter os dados vindos do formulário
			foreach ( $data as $key => $val )
			{

				$k = $this -> mapProperty($key);

				$set = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $k)));
				$get = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $k)));

				if ( method_exists($this, $set) )
				{

					if ( ! is_array($val) )
					{
						$this -> $set($data[$key]);
						$this -> attributes[$k] = $this -> $get();
					}
					else
					{

						if ( isset($_POST[$key]) )
						{
							$this -> $set($val);
							$this -> attributes[$k] = $this -> $get();
						}
						elseif ( isset($_FILES[$key]) )
						{
							$this -> $set($data);
							$this -> attributes[$k] = $this -> $get();
						}

					}

				}

			}

			$vars = get_class_vars(get_class($this));

			foreach ( $vars as $key => $val ) {

				$set = 'set' . str_replace(' ', '', ucwords(str_replace(['-', ' '], ' ', $key)));
				$get = 'get' . str_replace(' ', '', ucwords(str_replace(['-', ' '], ' ', $key)));

				if ( ! empty($val) && method_exists($this, $set) && method_exists($this, $get) && ! in_array($key, array_keys($this -> attributes)) ) {
					$this -> $set($val);
					$this -> attributes[$key] = $this -> $get();
				}

			}

			return $this -> attributes;

		}

		public function getAttributes($row){

			$dados = [];

			if ( is_object($row) ) {

				$this -> fill((array) $row);

			} else {

				if ( !empty($row) ){

					foreach($row as $key => $val) {

						$dados[] = $this -> getAttributes($val);

					}

				}

				
				return $dados;
				
			}
			// print_r($this -> attributes);

			return $this -> attributes;

		}

	}

}
