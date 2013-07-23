<?php

Router::mapResources('Api.ApiUsers');

$prefix = 'v[1]';

CroogoRouter::connect('/api/:prefix/users/:action/*', array(
	'plugin' => 'api',
	'controller' => 'api_users',
), array(
	'prefix' => $prefix,
));
