<?php

namespace Functions
{

	/**
	 * Minha própria classe criada para ser utilizada,
	 * por todo o sistema, sempre que necessário uma conexão com o banco de dados,
	 * em conjunto com o arquivo
	 *
	 * 		{\functions\Functions.php}
	 *
	 * onde tem todas as funções que invocam os métodos desta classe.
	 *
	 * Sempre utilizar métodos státicos nas funções para invocar
	 * os métodos da classe, como:
	 *
	 * Library::method_name($optionals_parameters);
	 *
	 * @author	Alisson Guedes
	 * @since	2019-09-17
	 * @since	Release 01
	 * @version	1.0
	 */
	class Library {

		/**
		 * Obtém a conexão com o banco de dados.
		 * Sempre que for necessário, invocar:
		 *
		 *		Library :: getConnect(nome_da_tabela)
		 *
		 * @param $table = nome da tabela na conexão
		 * @return $db -> table(nome_da_tabela)
		 *
		 */
		private static function getConnect()
		{

			$db = \Config\Database :: connect((ENVIRONMENT !== 'development' ) ? 'default' : 'tests');
			return $db;

		}

		/**
		 * Obtém informações das configurações do site.
		 * Busca as colunas para serem exibidas nas páginas, onde
		 * poderiam ter as mesmas informações, como telefone de contato, e-mail,
		 * nome do autor, etc. em todas em que seria necessário
		 * uma consulta sempre que o usuário abrir uma página diferente.
		 *
		 * Quando invocar a função \functions\Functions::configuracoes(),
		 * é esta função que será retornada.
		 *
		 * @param $column
		 * 		`site_description`, `site_title`, `site_name` [...]
		 * @param $table
		 * 		`tb_configuracao`, `tb_usuario`, entre outras tabelas
		 *
		 * @return $column
		 */
		public function configuracoes($column, $table = 'tb_configuracao', $where = array())
		{

			$column = trim($column);
			$db = Library :: getConnect();

			$from = $db -> table($table);
			$fields = $db -> getFieldNames($table);

			if ( ! in_array($column, $fields) ) {
				$from -> select('value')
					  -> where('config', $column)
					  -> where('status', '1');
			} else {
				$from = $db -> table($table);
				$from -> select($column . ' AS value');
			}

			if ( ! empty($where) )
				$from -> where($where);

			$row = $from -> get() -> getRowObject();

			if ( isset($row) ) {
				return $row -> value;
			} else {
				throw new \Exception( 'Column `' . $column . '`' . ' not found in table `' . $table . '`');
			}

		}

		/**
		 * Obtém as traduções das tabelas `tb_linguagem` e `tb_liguagem_traducao`
		 * @param $label = o nome do campo a ser traduzido
		 * $param $catch = Se a tradução não estiver disponível, exibir esta informação.
		 */
		public static function traduzir($label, $traducao) {

			$label = trim($label);
			$db = Library :: getConnect();
			$from = $db -> table('tb_linguagem_traducao');
			
			$idioma = $from -> select('traducao')
				-> where('lang_abr', configuracoes('language'))
				-> where('label', $label)
				-> limit(1)
				-> get()
				-> getRowObject();

			if ( isset($idioma) ) {
				return $idioma -> traducao;
			}
			
			return $traducao;

		}

		/**
		 * Obtém a lista de menu de acordo com as permissões do usuário se houver uma sessão
		 * @param $label = o nome do campo a ser traduzido
		 * $param $catch = Se a tradução não estiver disponível, exibir esta informação.
		 */
		public static function getMenu($local = 'sidebar', $id = 0) {

			$db = Library :: getConnect();

			$permissoes = Library :: getPermissions();
			$builder = $db -> table('tb_menu AS M');

			$COLUMNS = [
				'M.ordem', 'M.id AS id_menu', 'M.parent', 'M.link', 'M.label', 'M.target', 'M.icone'
			];

			$row = $builder -> select($COLUMNS);

			$builder -> where('M.parent', $id);

			$builder -> where('M.status', '1');

			$builder -> orderBy('M.ordem', 'ASC');
			$builder -> orderBy('M.label', 'ASC');

			$result = $row -> get() -> getResult();

			return $result;

		}

		public static function getPermissions() {

			if ( ! is_logged() )
				return false;

			$db = Library :: getConnect();

			$COLUMNS = [
				'P.id', 'P.id_controle', 'P.id_grupo', 'P.listar', 'P.inserir', 'P.editar', 'P.remover', 'P.status p_status',
				'M.id', 'M.diretorio', 'M.status m_status',
				'C.id', 'C.id_menu', 'C.controller', 'C.route',
				'G.id', 'G.status g_status',
				'GM.id', 'GM.id_grupo', 'GM.id_modulo'
			];

			$permissoes = $db -> table('tb_acl_permissao P')
							  -> select($COLUMNS)
							  -> join('tb_acl_controle C', 'P.id_controle = C.id', 'left')
							  -> join('tb_acl_grupo G', 'P.id_grupo = G.id', 'left')
							  -> join('tb_acl_grupo_modulo GM', 'GM.id_grupo = G.id', 'left')
							  -> join('tb_modulo M', 'M.id = GM.id_modulo', 'left')
							  
							  -> where('G.id', $_SESSION[USERDATA]['id_grupo'])
							  -> where('M.diretorio', basename(APPPATH))
							  -> where('G.status', '1')
							  -> where('P.status', '1')
							  -> where('M.status', '1');

			// echo $permissoes -> getCompiledSelect();

		}

	}

}