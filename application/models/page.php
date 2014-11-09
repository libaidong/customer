<?
class Page extends Joyst_Model {
	function DefineSchema() {
		$this->On('create', function(&$row) {
			$row['created'] = time();
			$row['creatorid'] = $this->User->GetActive('userid');
		});
		$this->On('save', function($id, &$row) {
			$row['edited'] = time();
		});
		return array(
			'_model' => 'Page',
			'_table' => 'pages',
			'_id' => 'pageid',
			'pageid' => array(
				'type' => 'pk',
				'readonly' => true,
			),
			'code' => array(
				'type' => 'varchar',
				'length' => 100,
			),
			'title' => array(
				'type' => 'varchar',
				'length' => 255,
			),
			'type' => array(
				'type' => 'enum',
				'options' => array(
					'text' => 'Text',
					'html' => 'HTML',
				),
				'default' => 'text',
			),
			'created' => array(
				'type' => 'epoc',
				'readonly' => true,
			),
			'creatorid' => array(
				'type' => 'int',
				'readonly' => true,
			),
			'edited' => array(
				'type' => 'int',
				'readonly' => true,
			),
		);
	}

	/**
	* Takes an array of vars and replaces all those inside square brackets with the equivelent value
	* @param string $text The blob of text to process
	* @param array $vars The array of PHP variables to replace
	*/
	function Replace($text, $vars) {
		$replacements = array();
		foreach ($vars as $k => $v)
			$replacements["[$k]"] = $v;
		return strtr($text, $replacements);
	}
}
