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
		 * 		`description`, `title`, `name` [...]
		 * @param $table
		 * 		`tb_configuracao`, `tb_usuario`, entre outras tabelas
		 *
		 * @return $column
		 */
		public static function configuracoes(string $column, string $table = null, array $where = array())
		{

			$db = Library :: getConnect();

			$table = !is_null($table) ? $table : 'tb_sys_config';

			$column = trim($column);

			$from = $db -> table($table);
			$fields = $db -> getFieldNames($table);

			if ( in_array($column, $fields) ) {

				$from = $db -> table($table);
				$from -> select($column);

				if ( ! empty($where) )
					$from -> where($where);

				$row = $from -> get() -> getRowObject();

                if (isset($row)) {
                    return $row -> $column;
                }

			}

			throw new \Exception('`' . $column . '` Não existe na tabela `' . $table . '`');

		}

		public static function tradutor(string $label = null) {

			$params = [];
			$parser = \Config\Services::parser();

			$db = Library :: getConnect();

			$builder = $db -> table('tb_sys_linguagem_traducao');

			$builder -> select('label, traducao');

			if ( ! is_null($label) ) {
				$result = $builder -> where('label', $label)
								   -> get()
								   -> getRow();

				return $result -> traducao;
			}

			$result = $builder -> get() -> getResult();

			if ( !empty($result) ) {

				foreach($result as $row ) {
	
					if (!defined($row -> label)) {
						define($row -> label, $row -> traducao);
						$params[$row -> label] = $row -> traducao;
					}
	
				}
	
			}

			return $params;

		}

		public static function getPermissoes() {

			$db = Library :: getConnect();
			$builder = $db -> table('tb_acl_menu_grupo AS P');

			$builder -> join('tb_acl_menu AS M', 'M.id = P.id_menu', 'left');
			$builder -> join('tb_acl_grupo AS G', 'G.id = P.id_grupo', 'left');
			$builder -> join('tb_acl_usuario AS U', 'U.id_grupo = G.id', 'left');
			$builder -> join('tb_acl_rotas AS R', 'R.id_menu = M.id', 'left');

			if (is_logged()) {
				$builder -> where('U.id', $_SESSION[USERDATA]['id'])
						 -> where('U.id_grupo', $_SESSION[USERDATA]['id_grupo']);
			}

			$builder -> groupStart()
				-> orGroupStart()
					-> where('M.permissao <= U.permissao')
					-> where('R.permissao <= U.permissao')
				-> groupEnd()
				-> orGroupStart()
					-> where('M.permissao <= G.permissao')
					-> where('R.permissao <= G.permissao')
				-> groupEnd()
			-> groupEnd()
			
			-> where('G.permissao > 0')


			-> where('M.permissao > 0')
			-> where('R.permissao > 0')

			-> where('M.status', '1')
			-> where('R.status', '1');

			return $builder;
		}

		/**
		 * Obtém a lista de menu de acordo com as permissões do usuário se houver uma sessão
		 * @param $label = o nome do campo a ser traduzido
		 * $param $catch = Se a tradução não estiver disponível, exibir esta informação.
		 */
		public static function getMenu($local = 'sidebar', $id = 0) {

			$builder = Library :: getPermissoes();

			$COLUMNS = [ 'M.id', 'M.id_secao', 'M.id_parent', 'M.label', 'M.link', 'M.target', 'M.ordem', 'M.permissao', 'M.status', 'M.icon' ];

			$row = $builder -> select($COLUMNS);
			$builder -> distinct(true);

			$builder -> join('tb_acl_menu_secao AS S', 'S.id = M.id_secao', 'left');
			$builder -> join('tb_acl_modulo AS Mod', 'Mod.id = S.id_modulo', 'left');

			if ( !is_null($local) )
				$builder -> where('S.slug', $local);

			$builder -> where('M.id_parent', $id)
					 -> where('Mod.diretorio', basename(BASEPATH))

					 -> orderBy('M.ordem', 'ASC')
					 -> orderBy('M.label', 'ASC');

			$result = $builder -> get() -> getResult();

			return $result;

		}

		public static function getRoutes($id = 0) {

			$rotas = [];
			$permissoes = Library :: getMenu();

			$COLUMNS = [ 'R.id', 'R.type', 'R.id_menu', 'R.route', 'R.controller', 'R.filter', 'R.permissao' ];

			$builder = Library :: getPermissoes();
			$builder -> select($COLUMNS);
			$builder -> distinct(true);

			$result = $builder -> get() -> getResult();

			if ( !empty($result) ) {
				foreach ( $result as $rows ) {
					$rotas[] = $rows;
				}
			}

			return $rotas;

		}

	}

}
