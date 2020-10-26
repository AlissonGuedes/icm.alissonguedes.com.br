<?php 

namespace App\Validations {

	class SiteValidation extends AppValidation {

		public function getAllowedFields() {

			$this -> allowedFields =  [
				'name',
				'title',
				'url',
				'description',
				'keywords',
				'custodian',
				'expires',
				'revisit_after',
				'rating',
				'robots',
				'theme_color',
				'logomarca',
				'language',
				'msg_manutencao',
				'msg_suspensao',
				'manutencao',
				'bloqueado',
				'force_www',
				'force_https'
			];

			return $this -> allowedFields;

		}

		public function getRules() {

			$validate['title'] = array('trim', 'required');
			$validate['url'] = array('trim', 'required', 'is_unique[tb_sys_config.url,id,{id}]');
			
			return $validate;

		}

	}

}