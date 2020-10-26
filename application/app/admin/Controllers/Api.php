<?php

namespace App\Controllers {

	class Api extends AppController {

		//--------------------------------------------------------------------

		public function __construct() {

		}

		//--------------------------------------------------------------------

		public function token()
		{

			if ( isset($_SESSION[USERDATA]['token']))
				echo json_encode(['token' => $_SESSION[USERDATA]['token']]);
			else
				echo json_encode(['token' => null ]);
	
		}

		//--------------------------------------------------------------------

	}

}