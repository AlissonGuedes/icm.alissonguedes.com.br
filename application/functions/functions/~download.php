<?php

if ( ! function_exists('force_download') )
{
	/**
	 * Force Download
	 *
	 * Generates headers that force a download to happen
	 *
	 * @param	string	filename
	 * @param	mixed	the data to be downloaded
	 * @param	bool	whether to try and send the actual file MIME type
	 * @return	void
	 */

	function force_download($filename)
	{

		$filename;

		if ( is_file($filename) )
		{
			header('Content-Type: application/octet-stream');
			header('Content-Size: ' . filesize($filename));
			header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
			header('Content-Length: ' . filesize($filename));
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: private, no-transform, no-store, must-revalidate');
			readfile($filename);
			exit();
		}
		else
		{
			show_error('
							<p>Arquivo não encontrado: <b>' . basename($filename) . '</b></p>
							<p>Por favor, entre em contato com o desenvolvedor através deste e-mail
							<a href="mailto:"' . config_item('webmaster') . '>' . config_item('webmaster') . '</a> e informe este erro.</p>
						', 'Erro ao Tentar Baixar Arquivo');
		}

	}

}
