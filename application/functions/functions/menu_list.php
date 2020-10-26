<?php

function get_menu_list($user, $local = 'sidebar')
{

	$CI = DB();

	if ( ! isset($user) )
		exit('Não foi possível abrir a lista de menus: User undefined');

	$permissoes = get_permissoes($user);

	// print_r($permissoes);

	if ( $permissoes -> num_rows() > 0 )
	{

		foreach ( $permissoes -> result() as $perm )
		{
			// -- MENU.rota,

			$CI -> select('
					MENU.ordem,
					MENU.id id_menu,
					MENU.parent,
					MENU.link,
					MENU.label,
					MENU.target,
					MENU.icone,
					SECAO.id id_secao,
					SECAO.secao,
					CONTROLLER.route,
					CONTROLLER.controller,
					CONTROLLER.action
				');

			$CI -> from('tb_menu MENU');
			$CI -> join('tb_menu_secao SECAO', 'MENU.secao = SECAO.id', 'left');
			$CI -> join('tb_acl_controle CONTROLLER', 'CONTROLLER.id_menu = MENU.id', 'left');
			$CI -> where('MENU.id', $perm -> id_menu);
			$CI -> where('MENU.status', '1');
			$CI -> limit(1);

			// echo $CI-> get_compiled_select();

			if ( $local !== NULL )
			{
				$CI -> where('SECAO.slug', limpa_string($local));
			}

			$CI -> order_by('MENU.ordem', 'ASC');

			// echo $CI-> get_compiled_select();

			$query = $CI -> get();

			if ( $query -> num_rows() > 0 )
			{
				foreach ( $query -> result() as $mn )
				{
					$menus[] = $mn;
					// $menu
				}
			}

		}

		return ! empty($menus) ? $menus : null;

	}

}

function menu_list($user, $section = NULL)
{

	$menu = array();

	$local = $section != NULL ? $section : NULL;

	// $permissoes = &load_class('Permissoes', 'permissoes');

	$query = get_menu_list($user, $local);

	if ( count($query) > 0 )
	{

		sort($query);

		echo '<!-- . menu-title --><p class="menu-title"> <!-- <i class="fa fa-folder"></i> &nbsp; --> ' . $query[0] -> secao . '</p><!-- . end menu-title -->';

		foreach ( $query as $row )
		{

			$menu[$row -> parent][$row -> id_menu] = array(
				'id' => $row -> id_menu,
				'parent' => $row -> parent,
				'item' => $row -> label,
				'rota' => base_url() . $row -> link,
				'target' => $row -> target,
				'icone' => $row -> icone,
				'ordem' => $row -> ordem
			);

		}

	}

	return get_menus($menu);

}

function get_menus(Array $menu, $parent = 0, Array $config = array())
{

	if ( ! empty($menu) )
	{

		$a[$parent] = $menu[$parent];

		echo '<ul class="' . (isset($config['sub-menu']) ? $config['sub-menu'] : '') . '">';

		foreach ( $menu[$parent] as $submenu => $item )
		{

			// $link = (isset($menu[$submenu])) ? 'javascript:void(0);' : $item['rota'];
			$link = $item['rota'];
			$arrow = (isset($menu[$submenu])) ? '<span class="arrow"></span>' : '';
			$icone = (isset($item['icone'])) ? '<i class="' . $item['icone'] . '"></i>' : '';

			if ( isset($item['item']) && $item['item'] == 'Mensagens' )
			{

				$CI = &get_instance();
				$CI -> db -> select('COUNT(c_id) AS qtd_msg');
				$CI -> db -> where('c_status', '0');
				$count = $CI -> db -> get('tb_contato') -> result();
				$arrow = '&nbsp;&nbsp;';
				$arrow .= '	<span class="badge badge-important animated bounceIn">
							<span id="contador">
							' . number_format($count[0] -> qtd_msg, 0, '', '.') . '
							</span>
						</span>';

			}

			// $tooltip = 'data-title="' . $item['item'] . '" data-placement="right"
			// data-toggle="tooltip" title="' . $item['item'] . '"';

			$tooltip = 'title="' . $item['item'] . '"';

			echo '<li>
					<a href="' . $link . '" id="' . limpa_string($item['item']) . '" ' . $tooltip . '>
					' . $icone . '
					<span class="title"> ' . $item['item'] . ' </span>
					' . $arrow . '</a>';

			if ( isset($menu[$submenu]) )
				get_menus($menu, $submenu, array('sub-menu' => 'sub-menu'));

			echo '</li>';

		}

		echo '</ul>';

	}

}
