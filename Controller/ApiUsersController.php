<?php

App::uses('AppController', 'Controller');

class ApiUsersController extends AppController {

	public $uses = array(
		'Users.User',
	);

	public $components = array(
		'Api.Api' => array(
			'prefix' => 'v1',
		),
	);

	public function v1_me() {
		$accessToken = $this->Api->accessToken();
		$user = $this->User->findById($accessToken['AccessToken']['user_id']);
		$this->log($user);
		if ($user) {
			$fields = array(
				'id', 'username', 'name', 'website', 'image', 'bio', 'timezone',
				'created', 'updated',
			);
			$fields = array_combine($fields, array_fill(0, count($fields), null));
			$user = array_intersect_key($user['User'], $fields);
		}
		$this->set(compact('user'));
		$this->set('_serialize', 'user');
	}

}
