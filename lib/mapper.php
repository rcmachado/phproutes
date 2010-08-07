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
			$matches = $this->_match($_url, $url);
			if ($matches !== false) {
				if (!empty($params['params'])) {
					$info = $params['params'];
				} else {
					$info = array();
				}

				if ($params['variables']) {
					$as_keys = array_flip($params['variables']);

					$filtered = array_intersect_key($matches, $as_keys);
					$info = array_merge($info, $filtered);
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

	private function _match($re_url, $url) {
		$re_url = preg_replace('/\{(\w+)\}/', '(?P<\\1>\w+)', $re_url);
		$quoted_url = str_replace('/', '\/', $re_url);

		if (!preg_match("/^$quoted_url\$/", $url, $matches)) {
			return false;
		}

		return $matches;
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