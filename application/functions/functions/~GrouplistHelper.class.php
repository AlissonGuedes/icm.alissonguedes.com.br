<?php

class GrouplistHelper {

	public $filhos = array();

	public function getHtml()
	{

		/**
		 *	<div class="dd" id="nestable">
		 *		<ol class="dd-list">
		 *			<li class="dd-item" data-id="<?php ?>">
		 *				<div class="dd-handle">
		 *					< >
		 *				</div>
		 *			 </li>
		 *		</ol>
		 *	</div>
		 */

		$html = '';

		if ( ! empty($this -> filhos))
		{

			// #nestable
			$html .= '<div class="dd" id="nestable">';

			foreach ($this -> filhos as $filho)
			{

				$html .= '<li class="">';

				//

				$html .= '</li>';

			}

			// end #nestable
			$html .= '</div>';

		}

	}

}
