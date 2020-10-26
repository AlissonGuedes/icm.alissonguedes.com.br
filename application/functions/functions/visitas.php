<?php

/**
 * Sistema contador de visitas
 *
 * Usado para realizar a contagem de visitas únicas e as páginas visitadas do
 * site
 *
 * @author Alisson Guedes <desenvolvimento@stoledo.com.br
 * @link http://www.stoledo.com.br
 *
 * @version 2.5.0
 * @package application/sistem/functions
 *
 */

function registrarVisita()
{

	$CI = &get_instance();

	$geolocation = new Geolocation();

	$microtime_full = microtime(TRUE);
	$microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
	$datetime = new DateTime(date('Y-m-d H:i:s.' . $microtime_short, $microtime_full));

	$geolocation -> locate(getIpAddress());
	$location = $geolocation -> result('latitude') . ',' . $geolocation -> result('longitude');

	$query = $CI -> db -> select('*')
	/*				   */ -> from('tb_visitante')
	/*				   */ -> where('vi_data', $datetime -> format('Y-m-d'))
	/*				   */ -> where('vi_ip', $geolocation -> result('ip'))
	/*				   */ -> limit('1')
	/*				   */ -> get();

	if ( $query -> num_rows() == 0 )
	{

		$id_session = strtotime($datetime -> format('Y-m-d H:i:s'));

		$query = $CI -> db -> set('vi_ip', $geolocation -> result('ip'))
		/*				   */ -> set('vi_data', $datetime -> format('Y-m-d'))
		/*				   */ -> set('vi_hora', $datetime -> format('H:i:s'))
		/*				   */ -> set('vi_count', 1)
		/*				   */ -> set('vi_pageviews', 1)
		/*				   */ -> set('vi_lat_long', $location)
		/*				   */ -> set('vi_idsessao', $id_session)
		/*				   */ -> insert('tb_visitante');

		$CI -> session -> set_userdata('CONTADOR_VISITAS');

	}
	else
	{

		foreach ( $query -> result() as $dados )
		{
			$id = $dados -> vi_id;
			$ip = $dados -> vi_ip;
			$data = $dados -> vi_data;
			$hora = $dados -> vi_hora;
			$count = $dados -> vi_count;
			$pageviews = $dados -> vi_pageviews;
			$localizacao = $dados -> vi_lat_long;
			$sessao = $dados -> vi_idsessao;
		}

		$query = $CI -> db -> set('vi_hora', $datetime -> format('H:i:s'))
		/*				   */ -> set('vi_pageviews', $pageviews + 1)
		/*				   */ -> where('vi_idsessao', $sessao)
		/*				   */ -> update('tb_visitante');

	}

}

/**
 * Obtém o endereço ip do usuário
 */

function getIpAddress($ipaddress = NULL)
{

	if ( ! isset($ipaddress) )
	{

		if ( ! empty($_SERVER['HTTP_CLIENT_IP']) )
		{
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
		{
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

	}
	else
	{

		$ip = $ipaddress;

	}

	return $ip;

}
