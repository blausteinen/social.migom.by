<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
            'import' => array(
                'api.models.*',
                'api.components.*',
                'api.controllers.*',
            ),
            'modules' => array(
                'api',
            ),
            'params' => array('api' => array(
                    'key' => 'devel',
                    'suid' => '3da7b280eda538c15f2bff38afd11dcd',
                    'user_puid' => 'asd',
                    'user_id' => 100,
                ),
            ),
//		'components'=>array(
//			'fixture'=>array(
//				'class'=>'system.test.CDbFixtureManager',
//			),
//		),
	)
);
