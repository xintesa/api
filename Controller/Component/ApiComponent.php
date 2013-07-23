<?php

App::uses('Component', 'Controller/Component');

class ApiComponent extends Component {

/**
 * Components
 */
	public $components = array(
		'Auth',
	);

/**
 * Access Token
 */
	protected $accessToken;

/**
 * Gets access token
 */
	public function accessToken() {
		return $this->_accessToken;
	}

/**
 * Startup
 */
	public function initialize(Controller $controller) {
		$prefix = null;
		if (isset($controller->request->params['prefix'])) {
			$prefix = $controller->request->params['prefix'];
		}

		if (in_array($prefix, (array)$this->settings['prefix'])) {
			$this->_accessToken = $this->_verifyAccessToken($controller);
			if (empty($this->request->params['ext'])) {
				$this->viewClass = 'Json';
			}

			// TODO: implement proper ACL for API endpoints
			$this->Auth->allow($controller->request->params['action']);
		}
	}

/**
 * Verify request access token
 */
	protected function _verifyAccessToken($controller) {
		if (empty($controller->request->query['access_token'])) {
			throw new UnexpectedValueException('Missing access_token');
		}
		$AccessToken = ClassRegistry::init('OAuth.AccessToken');
		$accessToken = $AccessToken->find('first', array(
			'oauth_token' => $controller->request->query['access_token'],
		));

		if (empty($accessToken['AccessToken']['user_id'])) {
			throw new UnauthorizedException('Invalid access_token');
		} else {
			return $accessToken;
		}
	}
}
