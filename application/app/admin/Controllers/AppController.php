<?php
namespace App\Controllers {

	/**
	 * Class AppController
	 *
	 * AppController provides a convenient place for loading components
	 * and performing functions that are needed by all your controllers.
	 * Extend this class in any new controllers:
	 *     class Home extends AppController
	 *
	 * For security be sure to declare any new methods as protected or private.
	 *
	 * @package CodeIgniter
	 */

	use CodeIgniter\Controller;

	class AppController extends Controller
	{

		/**
		 * An array of helpers to be loaded automatically upon
		 * class instantiation. These helpers will be available
		 * to all other controllers that extend AppController.
		 *
		 * @var array
		 */
		protected $helpers = [];

		//--------------------------------------------------------------------

		/**
		 * Constructor.
		 */
		public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
		{
			// Do Not Edit This Line
			parent::initController($request, $response, $logger);

			//--------------------------------------------------------------------
			// Preload any models, libraries, etc, here.
			//--------------------------------------------------------------------
			// E.g.:
			// $this->session = \Config\Services::session();
			$this -> session = \Config\Services::session();
			$this -> uri = \Config\Services :: uri(current_url());
			$this -> parser = \Config\Services::parser();

			$this -> response = $response;

			$this -> translations = \Functions\Library::tradutor();
		
		}

		//--------------------------------------------------------------------

		/**
		 * Retorna a página com o layout padrão
		 * @method	view
		 * @param 	string	$view
		 * @param	array	$params
		 * @param	array	$options
		 * @return	/Views/templates/header.phtml
		 */
		public function view($view, $params = [], array $options = []): string {

			$header	 = $this -> header();
			$footer  = $this -> footer();
			$sidebar = $this -> sidebar();

			$view	 = view($view, $params, $options);

			require(VIEW_TEMPLATES . 'html.phtml');

			return '';

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna um template com código html para a classe \CodeIgniter\View\Parser
		 * @method	view
		 * @param 	string	$view		- o template a ser retornado
		 * @param	array	$params		- parâmetros para substituição dentro do template
		 * @param	boolean	$saveData	- se TRUE, os parâmetros serão retidos para chamadas subsequentes
		 * @return	/Views/templates/header.phtml
		 */
		public function template($template, array $params = [], bool $saveData = true): string {

			$params['hide_menu']	= configuracoes('status', 'tb_acl_usuario');
			$params['base_url']	= base_url();
			$params['base_path'] = base_path();

			foreach( $this -> translations as $ind => $val ) {
				$params[$ind] = $val;
			}

			$view = $this -> parser -> setData($params)
					   				-> render($template, ['cascadeData' => $saveData]);

			return $view; 

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna apenas uma página sem estilização para retorno de chamadas ajax com JSON
		 * @method	view
		 * @param	string	$view		- o arquivo com a página que será enviada
		 * @param	array	$dados		- os dados que serão retornados dentro da view
		 * @return	string	$view
		 */
		public function json($view, $params = []): string {

			return view($view, $params);

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna o cabeçalho, quando existir, comum a todas as páginas na view
		 * @method header
		 * @param  array $params
		 * @return /Views/templates/header.phtml
		 */
		public function header($params = []): string {

			if ( file_exists(VIEW_TEMPLATES . 'header.phtml') )
				return $this -> template('templates/header', $params);

			return '';

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna a barra de menus, quando existir, comum a todas as páginas na view
		 * @method sidebar
		 * @param  array $params
		 * @return /Views/templates/sidebar.phtml
		 */
		public function sidebar($params = []): string {

			$params['sidebar'] = $this -> parser -> setData($params) -> renderString(buildMenu('sidebar'));

			if ( file_exists(VIEW_TEMPLATES . 'sidebar.phtml') )
				return $this -> template('templates/sidebar', $params);

			return '';

		}

		//--------------------------------------------------------------------

		/**
		 * Retorna o rodapé, quando existir, comum a todas as páginas na view
		 * @method footer
		 * @param  array $params
		 * @return /Views/templates/footer.phtml
		 */
		public function footer($params = []): string {

			if ( file_exists(VIEW_TEMPLATES . 'footer.phtml') )
				return $this -> template('templates/footer', $params);

			return '';

		}

		//--------------------------------------------------------------------

	}

}
