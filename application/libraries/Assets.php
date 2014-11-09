<?php

class Assets {

	protected $types = array('css', 'js');

	public function __construct()
	{
		$this->ci = & get_instance();
		$this->ci->load->helper('html');
	}

	/**
	 * Aggregate the javascript files required
	 *
	 * @param string $file
	 */

	public function add_js($file = '')
	{
		$header_js = $this->ci->config->item( 'header_js' );

		if ( empty( $file ) ) {
			return;
		}

		if ( is_array( $file ) ) {

			if ( !is_array( $file ) && count( $file ) <= 0 ) {
				return;
			}
			foreach ( $file AS $item ) {
				$header_js[ ] = $item;
			}
			$this->ci->config->set_item( 'header_js', $header_js );
		} else {
			$str          = $file;
			$header_js[ ] = $str;
			$this->ci->config->set_item( 'header_js', $header_js );
		}
	}

	/**
	 * Aggregate the css files
	 *
	 * @param string $file
	 */

	public function add_css($file = '')
	{
		$header_css = $this->ci->config->item( 'header_css' );

		if ( empty( $file ) ) {
			return;
		}

		if ( is_array( $file ) ) {
			if ( !is_array( $file ) && count( $file ) <= 0 ) {
				return;
			}
			foreach ( $file AS $item ) {
				$header_css[ ] = $item;
			}

			$this->ci->config->set_item( 'header_css', $header_css );
		} else {
			$str          = $file;
			$header_css[ ] = $str;
			$this->ci->config->set_item( 'header_css', $header_css );
		}
	}

	/**
	 * Prepare files for the page
	 *
	 * @return string
	 */

	public function put_headers()
	{
		$header_css = $this->ci->config->item( 'header_css' );
		$header_js  = $this->ci->config->item( 'header_js' );

		$css = $this->_filter_assets($header_css, 'css');
		$js = $this->_filter_assets($header_js, 'js');

		$assets = $css . $js;

		return $assets;
	}

	/**
	 * Aggrgate all the files and prepare for them to be rendered
	 *
	 * @param $assets
	 * @param $type
	 *
	 * @return string
	 */

	protected function _filter_assets($assets, $type)
	{
		$str = '';
		if(is_array($assets)) {
			foreach ( $assets AS $path ) {

				$str .= $this->_display_asset($path, $type);
			}
		}
		return $str;
	}

	/**
	 *
	 *
	 * @param $attr
	 * @param $path
	 * @param $type
	 *
	 * @return string
	 */

	protected function _display_asset($path, $type)
	{
		$str = '';
		switch($type) {
			case 'css':
				$extension = explode('.', $path);
				$extension = array_pop($extension);
				// Allow less files and prepare to convert to css
				if( $extension == 'less' ) {
					$path = array(
						'href' => base_url() . $path,
						'rel' => 'stylesheet/less',
						'type' => 'text/css'
					);
				} else {
					$path = base_url() . $path;
				}
				$str .= link_tag($path)."\n";
				break;
			case 'js':
				if(stripos($path, 'http://') !== false || substr($path, 0, 2) !== "//") {
					$path = base_url() . $path;
				}
				$str .= '<script type="text/javascript" src="' . $path . '"></script>' . "\n";
				break;
		}
		return $str;
	}
}