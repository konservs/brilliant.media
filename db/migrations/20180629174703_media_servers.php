<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class MediaServers extends AbstractMigration{
	/**
	 *
	 */
	public function change(){
		$table = $this->table('media_servers',array('id' => false, 'primary_key' => array('id')));
		$table->addColumn('id', 'integer', ['signed'=>false, 'identity'=>true, 'limit' => MysqlAdapter::INT_TINY])
			->addColumn('name', 'char', ['length'=>100])
			//Status. O - Online, D - Down
			->addColumn('status', 'enum', ['values'=>['O','D'],'default'=>'O'])
			->addColumn('url', 'char', ['length'=>150])
			->addColumn('path', 'char', ['length'=>150])
			->addColumn('bytesuploaded', 'biginteger', ['signed'=>false, 'default' => 0])
			->addColumn('filesuploaded', 'integer', ['signed'=>false, 'default' => 0])
			->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
			->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
			->create();
		}
	}
