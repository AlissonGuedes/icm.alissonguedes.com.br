<?php

namespace App\Entities {

	class Membro extends AppEntity {

		private $datalogin;

		protected $id;
		protected $id_igreja;
		protected $nome;
		protected $email;
		protected $telefone;
		protected $horario;

		protected $datamap = array (
			'igreja' => 'id_igreja',
			'pass' => 'senha'
		);

		public function __construct() {

			parent::__construct();
			$this -> config = new \App\Entities\Configuracao();

		}

		public function setId($id) {
			$this -> id = $id;
		}

		public function getId() {
			return $this -> id;
		}

		public function setIdIgreja($id) {
			$this -> id_igreja = $id;
			return $this;
		}

		public function getIdIgreja() {
			return $this -> id_igreja;
		}

		public function setNome($nome) {
			$this -> nome = $nome;
		}

		public function getNome() {
			return $this -> nome;
		}

		public function setEmail($email) {
			$this -> email = $email;
		}

		public function getEmail() {
			return $this -> email;
		}

		public function setTelefone($login) {
			$this -> telefone = $login;
		}

		public function getTelefone() {
			return $this -> telefone;
		}

		public function setHorario(string $str = null) {
			$this -> horario = $str;
			return $this;
		}

		public function getHorario() {
			return $this -> horario;
		}

	}

}