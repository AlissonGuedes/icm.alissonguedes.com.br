<?php

class MenuHelper {

	public $id_menu;
	public $nivel;
	public $menu;
	public $link;
	public $icone;
	public $adm_privilegio;
	public $status;
	public $filhos = array();

	public function getMenunav()
	{

		$html = '';
		$array = array();

		$model = new Model();

		$sql = "SELECT * FROM tb_privilegios WHERE status = '1';";

		$query = $model -> select($sql);

		foreach ( $query as $p )
		{

			if ( $this -> status === '1' )
			{

				if ( $this -> adm_privilegio == '#' || empty($this -> adm_privilegio) )
				{

					if ( count($this -> filhos) > 0 )
					{

						if ( $this -> status == '1' && $this -> adm_privilegio != '#' )
						{
							$html = '<li><a href="' . RAIZ . $this -> link . '"><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </a></li>';
						}
						else
						{
							$html = '<li><a class="mm-subopen mm-fullsubopen" href="#mm-m1-p' . $this -> id_menu . '"></a><span><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </span></li>';
						}

					}

				}
				else
				{

					if ( $p[$this -> adm_privilegio] == '1' )
					{
						if ( $this -> status == '1' )
						{
							$html = '<li><a href="' . RAIZ . $this -> link . '"><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </a></li>';
						}
						else
						{
							$html = '<li><a class="mm-subopen mm-fullsubopen" href="#mm-m1-p' . $this -> id_menu . '"></a><span><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </span></li>';
						}
					}

				}

			}

		}

		return $html;

	}

	public function getGrouplist()
	{
		echo '<pre>';
		$html = '';
		$array = array();

		$dsn = 'mysql:host=localhost;port=3306;dbname=sicoob_borborema;charset=utf8';
		$usr = 'sicoob_borborema';
		$pwd = 'Ty%0?EzOTm5)';

		$pdo = new PDO($dsn, $usr, $pwd);
		$sth = $pdo -> prepare('SELECT * FROM tb_privilegios WHERE status = "1";');
		$sth -> execute();
		$query = $sth -> fetchAll(PDO :: FETCH_ASSOC);

		foreach ( $query as $p )
		{

			if ( $this -> status === '1' )
			{

				if ( $this -> adm_privilegio == '#' || empty($this -> adm_privilegio) )
				{

					if ( count($this -> filhos) > 0 )
					{

						if ( $this -> status == '1' && $this -> adm_privilegio != '#' )
						{
							$html = '<li><a href="' . RAIZ . $this -> link . '"><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </a></li>';
						}
						else
						{
							$html = '<li><a class="mm-subopen mm-fullsubopen" href="#mm-m1-p' . $this -> id_menu . '"></a><span><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </span></li>';
						}

					}

				}
				else
				{

					if ( $p[$this -> adm_privilegio] == '1' )
					{
						if ( $this -> status == '1' )
						{
							$html = '<li><a href="' . RAIZ . $this -> link . '"><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </a></li>';
						}
						else
						{
							$html = '<li><a class="mm-subopen mm-fullsubopen" href="#mm-m1-p' . $this -> id_menu . '"></a><span><i class="icon ' . $this -> icone . '"></i> ' . $this -> menu . ' </span></li>';
						}
					}

				}

			}

		}

		return $html;

	}

	public function getHtml()
	{

		$html = '';

		if ( ! empty($this -> filhos) )
		{
			$html .= '<ul id="mm-m1-p' . $this -> id_menu . '" class="mm-list mm-panel">';

			if ( $this -> nivel == '0' )
			{
				$html .= '<li class="mm-subtitle"><a class="mm-subclose" href="#mm-m1-p0">' . $this -> menu . ' </a></li>';
			}
			else
			{
				$html .= '<li class="mm-subtitle"><a class="mm-subclose" href="#mm-m1-p' . ($this -> nivel) . '">' . $this -> menu . '</a></li>';
			}

			foreach ( $this-> filhos as $filho )
			{

				if ( $filho -> link == '#' && $filho -> status != '0' )
				{
					if ( $filho -> status != '0' )
					{
						$html .= '<li><a class="mm-subopen mm-fullsubopen" href="#mm-m1-p' . $filho -> id_menu . '"></a><span> ' . $filho -> menu . '</span></li>';
					}
				}
				else
				{
					if ( $filho -> status != '0' )
					{
						$html .= '<li><a href="' . RAIZ . $filho -> link . '"> ' . $filho -> menu . '</a></li>';
					}
				}
			}
			$html .= '</ul>';

			if ( $this -> status != '0' )
			{
				foreach ( $this -> filhos as $menu )
				{
					$html .= $menu -> getHtml();
				}
			}
		}
		return $html;
	}

	// public function getMenuUsuario(){
	// $html = '<li><a href="#">' . $this->menu . '</a>';
	// if(!empty($this->filhos)){
	// $html .= '<ul>';
	// foreach($this->filhos as $filho){
	// $html .= $filho->getMenuUsuario();
	// }
	// $html .= '</ul>';
	// }
	// $html .= '</li>';
	// return $html;
	// }

	public function getSiteMap()
	{
		$html = '';
		if ( ! empty($this -> filhos) && $this -> link == "#" )
		{
			$html = '<li class="collapsable"><a href="#">' . $this -> menu . '</a>';
		}
		else
		{
			$html = '<li>
						<a href="front/index.html">
							<i class="icon fa fa-rocket"></i>
							' . $this -> menu . '
						</a>
					</li>';
		}
		if ( ! empty($this -> filhos) )
		{
			$html .= '<ul>';
			foreach ( $this->filhos as $filho )
			{
				$html .= $filho -> getSiteMap();
			}
			$html .= '</ul>';
		}
		$html .= '</li>';

		return $html;
	}

	/* ======================================================= *
	 *                    Seters                               *
	 * ======================================================= */

	private function setIdmenu($id)
	{
		$this -> id_menu = $id;
		return $this;
	}

	private function setIdmenupai($id)
	{
		$this -> id_menu_pai = $id;
		return $this;
	}

	private function setMenu($menu)
	{
		$this -> menu = $menu;
		return $this;
	}

	private function setLink($link)
	{
		$this -> link = $link;
		return $this;
	}

	private function setIcone($icon)
	{
		$this -> icone = $icon;
		return $this;
	}

	private function setStatus($status)
	{
		$this -> status = $status;
		return $this;
	}

	private function setFilhos($filhos)
	{
		$this -> filhos[] = $filhos;
		return $this;
	}

}
