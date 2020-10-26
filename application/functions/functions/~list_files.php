<?php

function open_dir($path = '', $types = array())
{
	$retorno = '';

	if ( $path !== '' )
	{

		chdir($path);

		if ( ! empty($types) && is_array($types) )
		{

			$dir = new DirectoryIterator($path);

			foreach ( $dir as $fileInfo )
			{

				$ext = strtolower($fileInfo -> getExtension());

				if ( in_array($ext, $types) )
				{
					$files[] = $fileInfo -> getFilename();
				}
			}

			return $files;

		}
		else
		{
			$retorno = 'Defina os tipos de arquivos para serem listados.';
		}

	}
	else
	{
		$retorno = 'Informe o diretório para leitura.';
	}

	return $retorno;

}
