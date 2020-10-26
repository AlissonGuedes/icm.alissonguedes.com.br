<?php

/**
 * Retorna a extensão de um arquivo
 */
function extname($filename)
{

	$ext = explode('.', basename($filename));
	return $ext[count($ext) - 1];
}

/**
 * Altera o nome do arquivo para md5
 */
function rename_file($filename)
{
	return md5($filename) . '.' . extname($filename);
}

/**
 * Realiza o upload do arquivo para o servidor
 */
function upload_files($file, $path, $dimensoes = array('width' => '', 'height'=> ''), $formato = 'fill')
{

	$wideimage = new WideImage();
	$path_thumb = $path . '_thumbs/';
	$formatos = array(
		'bmp',
		'jpg',
		'jpeg',
		'png',
		'gif'
	);

	// abortar se houver algum erro no arquivo
	if ( $file['error'] != 0 )
		return FALSE;

	// verifica se é possível mover o arquivo para a pasta escolhida
	if ( ! is_dir($path) )
		mkdir($path, 0755, TRUE);

	$renamed = rename_file($file['name']);

	if ( ! is_dir($path_thumb) && in_array(extname($renamed), $formatos) )
		mkdir($path_thumb, 0755, TRUE);

	$source = $path . $renamed;

	// verifica se o diretório foi criado
	if ( is_dir($path) )
		if ( move_uploaded_file($file['tmp_name'], $source) )
		{
			if ( in_array(extname($renamed), $formatos) )
			{
				if ( ! empty($dimensoes['width']) OR ! empty($dimensoes['height']) )
				{
					$thumb = WideImage :: load($source);
					$final = $thumb -> resize($dimensoes['width'], $dimensoes['height'], $formato);
					$final -> saveToFile($path_thumb . $renamed);
				}
			}
			return TRUE;
		}
		else
			return FALSE;
	else
		return FALSE;

}

/**
 * Remove caratecres especiais
 * Converte todos os caracteres de um arquivo para caixa baixa
 * Remove espaçamentos
 */
function limpa_string($string, $replace = '_')
{

	$output = array();
	$a = array('Á' => 'a','À' => 'a','Â' => 'a','Ä' => 'a','Ã' => 'a','Å' => 'a','á' => 'a','à' => 'a','â' => 'a','ä' => 'a','ã' => 'a','å' => 'a','a' => 'a','Ç' => 'c','ç' => 'c','Ð' => 'd','É' => 'e','È' => 'e','Ê' => 'e','Ë' => 'e','é' => 'e','è' => 'e','ê' => 'e','ë' => 'e','Í' => 'i','Î' => 'i','Ï' => 'i','í' => 'i','ì' => 'i','î' => 'i','ï' => 'i','Ñ' => 'n','ñ' => 'n','O' => 'o','Ó' => 'o','Ò' => 'o','Ô' => 'o','Ö' => 'o','Õ' => 'o','ó' => 'o','ò' => 'o','ô' => 'o','ö' => 'o','õ' => 'o','ø' => 'o','œ' => 'o','Š' => 'o','Ú' => 'u','Ù' => 'u','Û' => 'u','Ü' => 'u','U' => 'u','ú' => 'u','ù' => 'u','û' => 'u','ü' => 'u','Y' => 'y','Ý' => 'y','Ÿ' => 'y','ý' => 'y','ÿ' => 'y','Ž' => 'z','ž' => 'z');
	$string = strtr($string, $a);
	$regx = array(" ",".","+","@","#","!","$","%","¨","&","*","(",")","_","-","+","=",";",":",",","\\","|","£","¢","¬","/","?","°","´","`","{","}","[","]","ª","º","~","^","\'","\"");

	$replacement = str_replace($regx, '|', trim(strtolower($string)));
	$explode = explode('|', $replacement);

	for ( $i = 0; $i < count($explode); $i ++ )
	{
		if ( ! empty($explode[$i]) )
			$output[] = trim($explode[$i]);
		// if( empty($output[$i]) )
		// unset($output[$i]);
	}

	return implode($replace, $output);

}
