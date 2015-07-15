<?php return array_merge_recursive(
	require_once(__DIR__."/shared.php"),
	[
		'commandMap' => array(
				'migrate' => array(
				'class' => 'composer.yiiext.migrate-command.EMigrateCommand',
				'migrationPath' => 'application.migrations',
				'migrationTable' => 'tbl_migration',
				'applicationModuleName' => 'BIRD3',
				'migrationSubPath' => 'migrations',
				'connectionID'=>'db',
				'disabledModules' => array(
					# Empty.
		        ),
				'templateFile'=>'application.migration_template',
		    ),
		),
	]
);
