<?php

// helper cep
if ( ! function_exists('buscar_endereco') )
{

	function buscar_endereco($cep)
	{

		if ( ! $cep )
		{
			echo json_encode(array(
				'type' => 'error',
				'msg' => null,
				'fields' => array(
					'logradouro' => null,
					'localidade' => null,
					'complemento' => null,
					'bairro' => null,
					'uf' => null
				)
			));
			return false;
		}

		$ind = [];
		$val = [];
		$return = array(
			'type' => 'success',
			'msg' => '',
			'redirect' => false
		);

		$cep = str_replace('.', '', $cep);
		$cep = str_replace('-', '', $cep);

		if ( strstr($cep, '_') || strlen($cep) < 8 || strlen($cep) >= 9 )
		{
			$return = array(
				'type' => 'error',
				'msg' => 'CEP inválido.',
				'fields' => array(
					'logradouro' => null,
					'localidade' => null,
					'complemento' => null,
					'bairro' => null,
					'uf' => null
				)
			);
		}
		else
		{

			$url = 'https://viacep.com.br/ws/' . urlencode($cep) . '/json/unicode';
			$json = file_get_contents($url);
			$array = json_decode($json);

			if ( isset($array -> erro) && $array -> erro )
			{
				$return = array(
					'type' => 'error',
					'msg' => 'CEP não encontrado.',
					'fields' => array(
						'logradouro' => null,
						'localidade' => null,
						'complemento' => null,
						'bairro' => null,
						'uf' => null
					)
				);
			}
			else
			{

				foreach ( $array as $i => $v )
				{
					$ind[] = $i;
					$val[] = $v;
				}

				$fields = array_combine($ind, $val);

				if ( $fields['complemento'] != '' )
				{
					$fields['logradouro'] = $fields['logradouro'] . ' ' . $fields['complemento'];
					$fields['complemento'] = '';
				}

				$return['fields'] = $fields;
			}
		}

		return alert($return);

	}

}
