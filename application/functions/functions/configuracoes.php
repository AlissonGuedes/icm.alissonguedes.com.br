<?php

// function get_configuracoes($configuracao, $tabela = 'tb_configuracao', $where = array())
// {
// 
	// try
	// {
// 
		// // 		$CI = DB('admin');
		// $CI = \Config\Database :: connect('tests');
// 
		// // $admin = $CI -> load -> database('admin', TRUE);
// 
		// $admin = $CI;
		// $cfg = null;
		// $config = null;
// 
		// // retorne a variáviel que estiver no parâmetro
		// $select = $configuracao;
// 
		// // Adicione alguma cláusa para satisfazer condições
		// if ( ! empty($where) )
		// {
			// foreach ( $where as $ind => $val )
				// $admin -> where($ind, $val);
		// }
// 
		// // Execute a query no banco de dados
		// // 		$query = $admin -> select($select) -> get($tabela) -> row();
		// $sql = 'SELECT ' . $select . ' FROM ' . $tabela . ' LIMIT 1';
		// $query = $admin -> query($sql);
		// $row = $query -> getRow();
// 
		// // Se houver registros, retorne a configuração com o seu respectivo valor
		// if ( $query )
		// {
			// return $row -> $configuracao;
		// }
// 
		// // Se não for possível encontrar uma tabela válida, retorne este erro:
		// // return 'Erro ao pesquisar tabela';
// 
	// }
	// catch( Exception $e )
	// {
		// echo $e;
	// }
// 
	// return false;
// 
// }

function get_perfil($column, $field = NULL, $show_script = FALSE)
{

	$CI = &get_instance();
	$admin = $CI -> load -> database('admin', TRUE);

	if ( ! $CI -> session -> userdata('userData_administrador') )
		return false;

	$config = NULL;

	// retorne a variáviel que estiver no parâmetro
	$select = $column;

	$admin -> where('id', $_SESSION['userData_administrador']['id']);
	$admin -> select($select);

	$query = $admin -> limit(1) -> get('tb_usuario') -> row();

	$dados['img_perfil'] = file_exists('./assets/' . $query -> $select) ? base_path() . $query -> $select : base_path() . 'img/profiles/2.png';

	$dados['imagens'] = array();

	$CI -> load -> helper('directory');
	$CI -> load -> library('Session');

	$session = $CI -> session -> userdata('userData_administrador');

	$usr_dir = get_configuracoes('path') . '/img/usuario/' . $session['id'] . '/';

	if ( is_dir('./assets/' . $usr_dir) )
	{

		foreach ( directory_map('./assets/' . $usr_dir) as $row )
		{
			$file['file_dir'] = $usr_dir . $row;
			$file['file_name'] = $row;
			$dados['imagens'][] = $file;
		}

	}

	If ( $show_script )
	{
		echo "<script>
		function h() {
			$(document).ready(function()
			{
				$('.remove').hover(function(e)
				{
					e.preventDefault();
					$(this).css(
					{
						'height' : $(this).next('a').find('img').height(),
						'width'  : '117px',
						'background-color' : 'rgba(255,0,0,0.5)',
						'display' : 'block'
					}).children().css({
						'text-align' : 'center',
						'padding-top' : '25px',
						'width' : '100%',
						'height' : '100%'
					});
				}, function(){
					$(this).css(
					{
						'height' : 'auto',
						'width'  : 'auto',
						'background-color' : 'transparent'
					}).children().css({
						'text-align' : 'center',
						'padding-top' : '3px',
					});
				}).click(function(e){
					e.preventDefault();
					$(this).parent().remove();
					$.ajax({
						'type' : 'post',
						'url'  : '" . base_url() . "excluir_foto',
						'dataType' : 'json',
						'data' : {
							'imagem' : $(this).attr('id')
						},
						'success' : function(data)
						{
							notificacao(data);
						}
					});
				});
			});
		}

		function alterar_foto(file)
		{

			$(document).ready(function()
			{

				var form = $('#imagem').parents('form');
				var action = form.attr('action');
				var method = form.attr('method');
				var data = null;

				if ( typeof file !== 'undefined' )
				{
					data = { file };
				}

				form.ajaxSubmit(
				{

					url : action,
					type : method,
					dataType : 'json',
					data : data,

					uploadProgress : function(event, position, total, progress)
					{
						console.log(total);
					},
					beforeSend : function()
					{
					},
					success : function(data)
					{
						if(data.action !== 'excluir')
						{

							notificacao(data, form);
							$('#' + data.fields.id).attr('src', data.fields.value).attr('data-src', data.fields.value).attr('data-src-retina', data.fields.value);

							icone = '';
						}

					},
					error : function(request, status, error)
					{

						swal('Não foi possível concluir a requisição',
						{
							icon : 'error',
						});

						notificacao();

					}

				});

			});
		}
		</script>";
	}

	if ( $field !== NULL )
		return $dados[$field];

	return $dados;

}
