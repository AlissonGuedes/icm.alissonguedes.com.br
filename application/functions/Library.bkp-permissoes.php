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
		public function configuracoes($column, $table = 'tb_sys_config', $where = array())
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
			$from = $db -> table('tb_sys_linguagem_traducao');
			
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
		public static function getMenu($local = 'menu', $id = 0) {

			$db = Library :: getConnect();

			$builder = $db -> table('tb_acl_menu AS M');

			$COLUMNS = [
				'M.ordem', 'M.id AS id_menu', 'M.parent', 'M.link', 'M.label', 'M.target', 'M.icone', 'P.permissao'
			];

			$row = $builder -> select($COLUMNS);
			$builder -> distinct(true);

			$builder -> join('tb_acl_menu_secao S', 'S.id = M.id_secao', 'left');

			$builder -> join('tb_acl_modulo AS Mod', 'Mod.id = S.id_modulo', 'right');
			$builder -> join('tb_acl_menu_grupo P', 'P.id_menu = M.id', 'right');
			$builder -> join('tb_acl_controle C', 'C.id_menu = M.id', 'inner');

			if ( !is_null($local) )
				$builder -> where('S.slug', $local);

			$builder -> where('M.parent', $id);
			$builder -> where('M.status', '1');
			$builder -> orderBy('M.ordem', 'ASC');
			$builder -> orderBy('M.label', 'ASC');

            if (is_logged()) {
                $builder -> where('P.id_grupo', $_SESSION[USERDATA]['id_grupo']);
                if ($_SESSION[USERDATA]['id_grupo'] == 2) {
                    // Super Administratores
                    $builder -> where('P.permissao >=', '000');
                } else {
                    $builder -> where('P.permissao <=', '100');
                }
            }

			$builder -> where('Mod.diretorio', basename(BASEPATH));

			$builder -> where('P.status', '1');

			// echo $builder -> getCompiledSelect();
			$result = $builder -> get() -> getResult();

			return $result;

		}

		public static function getPermissoes() {

			// $permissao = array();
			// $db = Library :: getConnect();

			// $builder = $db -> table('tb_acl_permissao AS P');

			// $COLUMNS = [
			// 	'M.id AS menu', 'M.link AS subgroup', 'Mod.diretorio AS group', 'P.permissao'
			// ];

			// $builder -> select($COLUMNS);
			// $builder -> join('tb_menu AS M', 'M.id = P.id_menu', 'left');
			// $builder -> join('tb_menu_secao S', 'S.id = M.id_secao', 'left');
			// $builder -> join('tb_modulo AS Mod', 'Mod.id = S.id_modulo', 'right');

			// $builder -> groupStart();
			// 	if ( is_logged() )
			// 		$builder -> where('P.id_grupo', $_SESSION[USERDATA]['id_grupo']);
			// 	$builder -> orWhere('P.id_grupo', 0);
			// $builder -> groupEnd();

			// $builder -> where('Mod.diretorio', basename(BASEPATH));
			// $builder -> where('P.status', '1');

			// // echo $builder -> getCompiledSelect();
			// $result = $builder -> get() -> getResult();

			// return $result;

		}

		public static function getRoutes() {

			$rotas = [];
			$permissoes = Library :: getMenu();

			if ( ! empty($permissoes) ) {

				$db = Library :: getConnect();

				foreach ( $permissoes as $perm ) {

					$builder = $db -> table('tb_acl_controle AS C');
					$COLUMNS = [ 'C.id', 'C.type', 'C.id_menu', 'C.route', 'C.controller', 'C.filter', 'C.permissao' ];

					$builder -> select($COLUMNS);
					$builder -> where('id_menu', $perm -> id_menu);

					if ( is_logged() ) {
						if ($_SESSION[USERDATA]['id_grupo'] == 2 ) {
							$builder -> where('C.permissao <=', 0);
						} else {
							$builder -> where('C.permissao <=', $perm -> permissao);
						}
					}

// 					echo $builder -> getCompiledSelect() . ';

// ';
					// $result = $builder -> get() -> getResult();

					// if ( !empty($result) ) {
					// 	foreach ( $result as $rows ) {
					// 		$rotas[] = $rows;
					// 	}
					// }

				}

			}

			return $rotas;

		}

	}

}