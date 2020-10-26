<?php

namespace App\Entities {

	class User extends AppEntity {

		private $datalogin;

		protected $id;
		protected $id_grupo;
		protected $id_gestor;
		protected $nome;
		protected $imagem;
		protected $email;
		protected $login;
		protected $senha;
		protected $salt;
		protected $ultimo_login;
		protected $primeiro_login;
		protected $listar;
		protected $inserir;
		protected $editar;
		protected $excluir;
		protected $hide_menu;
		protected $bloqueado;
		protected $status = '1';

		protected $datamap = array (
			'grupo' => 'id_grupo',
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

		public function setIdGrupo($id) {
			$this -> id_grupo = $id;
			return $this;
		}

		public function getIdGrupo() {
			return $this -> id_grupo;
		}

		public function setIdGestor($id) {
			$this -> id_gestor = $id;
		}

		public function getIdGestor() {
			return $this -> id_gestor;
		}

		public function getNomeGestor($id = null)
		{
			if ( is_null($id) )
				return null;

			$gestor = new \App\Models\UserModel;
			$gestor = $gestor -> getGestor(['tb_usuario.id' => $id]) -> getRow();
			return $gestor -> nome;
		}

		public function setNome($nome) {
			$this -> nome = $nome;
		}

		public function getNome() {
			return $this -> nome;
		}

		/**
		 * Set Imagem do banner
		 *
		 * @param	$str
		 *        	String
		 * @return String
		 */
		public function setImagem($str = null)
		{

			if ( ! defined('USERDATA') || ! isset($_SESSION[USERDATA]) )
				return FALSE;

			if ( empty($str) )
				return false;

			if ( ! is_null($str) && is_string($str) )
			{
				$this -> imagem = $str;
				return $this;
			}
			else
			{

				if ( ! is_null($str) )
				{

					foreach ( $str as $ind => $val )
					{

						$path =  $_SERVER['DOCUMENT_ROOT'] . $this -> config -> getBasePath() . 'img/banner/';

						$file = $this -> request -> getFile($ind);

						if ( ! $file -> isValid() )
							return false;
						
						if ( ! is_dir($path) && is_writable($path) )
							mkdir($path, 0755, TRUE);

						$newName = $file -> getRandomName();
						$file -> move($path, $newName);

						$this -> imagem = $file -> getName();

					}

					return $this;

				}

			}

		}

		/**
		 * Get Imagem do banner
		 *
		 * @return String
		 */
		public function getImagem(bool $realpath = false)
		{

            if ( $realpath )
				return $this -> imagem;

			return $this -> config -> getBasePath() . 'img/banner/' . $this -> imagem;

		}

		public function setEmail($email) {
			$this -> email = $email;
		}

		public function getEmail() {
			return $this -> email;
		}

		public function setLogin($login) {
			$this -> login = $login;
		}

		public function getLogin() {
			return $this -> login;
		}

		public function setSenha(string $str = null) {
			$this -> senha = $str;
			return $this;
		}

		public function getSenha(bool $crypt = true) {

			if ( empty($this -> senha) )
				return 'Sua senha não foi alterada!';

			if ( ! $crypt )
				return $this -> senha;

			return hashCode($this -> senha);

		}

		public function setSalt(string $str = null) {
			$this -> salt = $str;
			return $this;
		}

		public function getSalt() {
			return $this -> salt;
		}

		public function setPrimeiroLogin(string $str = null) {

			if ( ! empty($str) ) {
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d H:i:s', strtotime($str));
				$this -> primeiro_login = $str;
			}

			return $this;

		}

		public function getPrimeiroLogin(string $format = 'Y-m-d H:i:s') {

			if ( ! empty($this -> primeiro_login) )
				return date($format, strtotime($this -> primeiro_login));

			return $this -> primeiro_login;

		}

		public function setUltimoLogin(string $str = null) {

			if ( ! is_null($str) )
				$this -> ultimo_login = $str;
			else
				return $this -> ultimo_login = null;

			$this -> datalogin = new \DateTime($this -> ultimo_login);

			return $this;

		}

		public function getUltimoLogin(string $format = 'Y-m-d H:i:s') {
			if ( ! empty($this -> ultimo_login) )
			{
				return $this -> datalogin -> format($format);
			}
		}

		public function setDataCadastro(string $str = null) {

			if ( ! empty($str) ) {
				$str = str_replace('/', '-', $str);
				$str = date('Y-m-d H:i:s', strtotime($str));
				$this -> data_cadastro = $str;
			}

			return $this;

		}

		public function getDataCadastro(string $format = 'Y-m-d H:i:s') {

			if ( ! empty($this -> data_cadastro) )
				return date($format, strtotime($this -> data_cadastro));

			return $this -> data_cadastro;

		}

		public function setHideMenu(string $str = null) {
			$this -> hide_menu = $str;
			return $this;
		}

		public function getHideMenu() {
			return $this -> hide_menu;
		}

		public function setStatus(string $str) {
			$this -> status = $str;
			return $this;
		}

		public function getStatus() {
			return $this -> status;
		}
	}

}