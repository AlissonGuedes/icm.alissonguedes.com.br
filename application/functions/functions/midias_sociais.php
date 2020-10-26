<?php

function get_midias_sociais($json_link = '', $usar_curl = false)
{
	if ( $usar_curl )
	{
		$linha = curl_init();
		curl_setopt($linha, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($linha, CURLOPT_URL, $json_link);
		$json_dados = curl_exec($linha);
		curl_close($linha);
		return json_decode($json_dados);
	}
	else
	{
		@$json_dados = file_get_contents($json_link);
		return json_decode($json_dados);
	}
}
