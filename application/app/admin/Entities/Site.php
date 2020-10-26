<?php

namespace App\Entities {

	class Site extends AppEntity {

		private $datalogin;

		protected $id;
		protected $title;
		protected $url;
		protected $description;
		protected $keywords;
		protected $robots;
		protected $force_https;
		protected $force_www;
		protected $msg_manutencao;
		protected $msg_suspensao;
		protected $manutencao;
		protected $bloqueado;
		protected $status = '1';

		protected $datamap = array ();

		public function __construct() {

			$this -> config = new \App\Entities\Configuracao();

		}

		public function setId($id) {
			$this -> id = $id;
		}

		public function getId() {
			return $this -> id;
		}

		public function setTitle(string $str = null)
		{
			$this -> title = $str;
			return $this;
		}

		public function getTitle()
		{
			return $this -> title;
		}

		public function setDescription(string $str = null)
		{
			$this -> descripition = $str;
			return $this;
		}

		public function getDescription()
		{
			return $this -> descripition;
		}

		public function setKeywords($str = null)
		{
			$this -> keywords = $str;
			return $this;
		}

		public function getKeywords()
		{
			print_r($this -> keywords);
			if ( ! empty($this -> keywords) )
				return implode(',', $this -> keywords);
		}

		public function setGenerator(string $str = null)
		{
			$this -> generator = $str;
			return $this;
		}

		public function getGenerator()
		{
			return $this -> generator;
		}

		public function setAuthor(string $str = null)
		{
			$this -> author = $str;
			return $this;
		}

		public function getAuthor()
		{
			return $this -> author;
		}

		public function setCreatorAddress(string $str = null)
		{
			$this -> creator_address = $str;
			return $this;
		}

		public function getCreatorAddress()
		{
			return $this -> creator_address;
		}

		public function setCustodian(string $str = null)
		{
			$this -> custodian = $str;
			return $this;
		}

		public function getCustodian()
		{
			return $this -> custodian;
		}

		public function setPublisher(string $str = null)
		{
			$this -> publisher = $str;
			return $this;
		}

		public function getPublisher()
		{
			return $this -> publisher;
		}

		public function setRevistAfter(string $str = null)
		{
			$this -> revisit_after = $str;
			return $this;
		}

		public function getRevistAfter()
		{
			return $this -> revisit_after;
		}

		public function setRating(string $str = null)
		{
			$this -> rating = $str;
			return $this;
		}

		public function getRating()
		{
			return $this -> rating;
		}

		public function setRobots(string $str = null)
		{
			$this -> robots = $str;
			return $this;
		}

		public function getRobots()
		{
			return $this -> robots;
		}

		public function setThemeColor(string $str = null)
		{
			$this -> theme_color = $str;
			return $this;
		}

		public function getThemeColor()
		{
			return $this -> theme_color;
		}

		public function getBasePath()
		{
			return $this -> basePath =  base_path(TRUE);
		}

		public function setPath(string $str = null)
		{

			$this -> path = dirname(base_path()) . $str;

			return $this;

		}

		public function getPath()
		{
			return $this -> path;
		}

		/**
		 * Set Logomarca
		 *
		 * @param
		 *        	String
		 * @return String
		 */
		public function setLogomarca($str = null)
		{

			if ( ! isset($_SESSION[USERDATA]) )
				return FALSE;

			if ( empty($str) )
				return false;

			if ( ! is_null($str) && is_string($str) )
			{
				$this -> logomarca = $str;
				return $this;
			}
			else
			{

				if ( ! is_null($str) )
				{

					foreach ( $str as $ind => $val )
					{

						$path = $_SERVER['DOCUMENT_ROOT'] . $this -> getBasePath() . 'img/logo/';

						$file = $this -> request -> getFile($ind);

						if ( ! $file -> isValid() )
							return false;

						if ( ! is_dir($path) )
							mkdir($path, 0777, TRUE);

						$newName = $file -> getRandomName();
						$file -> move($path, $newName);

						$this -> logomarca = $file -> getName();

					}

					return $this;

				}

			}

		}

		/**
		 * Get Logomarca
		 *
		 * @return String
		 */
		public function getLogomarca(bool $realpath = false)
		{

			if ( $realpath )
				return base_path() . $this -> logomarca;

			return $this -> getBasePath() . 'img/logo/' . $this -> logomarca;

		}

		public function setLanguage(string $str = null)
		{
			$this -> language = $str;
			return $this;
		}

		public function getLanguage()
		{
			return $this -> language;
		}

		public function setMsgManutencao(string $str = null)
		{
			$this -> msg_manutencao = strtr($str, array(
				// '{{email}}' => '<a href="mailto:' . $this -> getEmail() . '">' . $this -> getEmail() . '</a>',
				// '{{telefone}}' => '<a href="phone:' . $this -> getEmail() . '">' . $this -> getTelefone() . '</a>',
				// '{{celular}}' => '<a href="phone:' . $this -> getEmail() . '">' . $this -> getCelular() . '</a>'
			));
			return $this;
		}

		public function getMsgManutencao()
		{
			return $this -> msg_manutencao;
		}

		public function setMsgBloqueioTemporario(string $str = null)
		{
			$this -> msg_bloqueio_temporario = strtr($str, array(
				// '{{email}}' => '<a href="mailto:' . $this -> getEmail() . '">' . $this -> getEmail() . '</a>',
				// '{{telefone}}' => '<a href="phone:' . $this -> getEmail() . '">' . $this -> getTelefone() . '</a>',
				// '{{celular}}' => '<a href="phone:' . $this -> getEmail() . '">' . $this -> getCelular() . '</a>'
			));
			return $this;
		}

		public function getMsgBloqueioTemporario()
		{
			return $this -> msg_bloqueio_temporario;
		}

		public function setVersion(float $num = null)
		{
			$this -> version = $num;
			return $this;
		}

		public function getVersion()
		{
			return $this -> version;
		}

		/**
		 * Set Logomarca
		 *
		 * @param
		 *        	String
		 * @return String
		 */
		public function setLogomarcaNf($str = null)
		{

			if ( ! isset($_SESSION[USERDATA]) )
				return FALSE;

			if ( empty($str) )
				return false;

			if ( ! is_null($str) && is_string($str) )
			{
				$this -> logomarca_nf = $str;
				return $this;
			}
			else
			{

				if ( ! is_null($str) )
				{

					foreach ( $str as $ind => $val )
					{

						$path = $_SERVER['DOCUMENT_ROOT'] . $this -> getBasePath() . 'img/logonf/';

						$file = $this -> request -> getFile($ind);

						if ( ! $file -> isValid() )
							return false;

						if ( ! is_dir($path) )
							mkdir($path, 0777, TRUE);

						$newName = $file -> getRandomName();
						$file -> move($path, $newName);

						$this -> logomarca_nf = $file -> getName();

					}

					return $this;

				}

			}

		}

		/**
		 * Get Logomarca
		 *
		 * @return String
		 */
		public function getLogomarcaNf(bool $realpath = false)
		{

			if ( $realpath )
				return base_path() . $this -> logomarca_nf;

			return $this -> getBasePath() . '/img/logonf/' . $this -> logomarca_nf;

		}

		public function setStatus(string $str) {
			$this -> status = $str;
			return $this;
		}

		public function getStatus() {
			return $this -> status;
		}

		public function setManutencao(string $str) {
			$this -> manutencao = $str;
			return $this;
		}

		public function getManutencao() {
			return $this -> manutencao;
		}

		public function setBloqueado(string $str) {
			$this -> bloqueado = $str;
			return $this;
		}

		public function getBloqueado() {
			return $this -> bloqueado;
		}
	}

}
