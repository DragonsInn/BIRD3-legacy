<?php return array_merge_recursive(
	require_once(__DIR__."/shared.php"),
	[
		'theme'=>'dragonsinn',
		'components'=>[
			'session'=>[
				'class'=>"ext.redis.ARedisSession",
				"keyPrefix" => "BIRD3.Session."
			],
		]
	]
);
