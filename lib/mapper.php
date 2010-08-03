<?php
/**
 * Route Mapper
 *
 * @package default
 * @author Rodrigo Machado
 */
class PRMapper {
	private $_routes;

	/**
	 * Map an URL to controller/action
	 *
	 * @param string $name Name of route (optional)
	 * @param string $url URL
	 * @param array $params Other parameters
	 * @return void
	 */
	public function connect($name, $url, array $params = null) {
		$info = array('name' => $name);
		if ($params) {
			$info['params'] = $params;
		}

		$info['variables'] = $this->_parse_params_from_url($url);

		$this->_routes[$url] = $info;
	}

	public function match($url) {
		foreach ($this->_routes as $_url => $params) {
			$re_url = preg_replace('/\{\w+\}/', '(\w+)', $_url);
			$quoted_url = str_replace('/', '\/', $re_url);

			if (preg_match("/^$quoted_url\$/", $url, $matches)) {
				if (!empty($params['params'])) {
					$info = $params['params'];
				} else {
					$info = array();
				}

				if ($params['variables']) {
					$i = 1;
					foreach ($params['variables'] as $param) {
						$info[$param] = $matches[$i];
						$i++;
					}
				}
				return $info;
			}
		}

		return null;
	}

	public function generate($name, array $values = null) {
		foreach ($this->_routes as $url => $params) {
			if ($params['name'] === $name) {
				return $this->_fill_url_with_values($url, $values);
			}
		}

		return null;
	}

	private function _fill_url_with_values($url, $values) {
		foreach ($values as $param => $value) {
			$url = str_replace('{' . $param . '}', urlencode($value), $url);
		}
		return $url;
	}

	private function _parse_params_from_url($url) {
		preg_match_all('/\{(\w+)\}/', $url, $matches);
		return $matches[1];
	}
}
?>