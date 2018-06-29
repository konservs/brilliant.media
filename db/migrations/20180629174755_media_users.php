<?php
use Phinx\Migration\AbstractMigration;

class MediaUsers extends AbstractMigration{
	/**
	 *
	 */
	public function change(){
		$table = $this->table('media_users',array('id' => false, 'primary_key' => array('id')));
		$table->addColumn('id', 'integer', ['signed'=>false, 'identity'=>true])
			->addColumn('bytesuploaded', 'biginteger', ['signed'=>false, 'default' => 0])
			->addColumn('bytesquota', 'biginteger', ['signed'=>false, 'default' => 0])
			->addColumn('filesuploaded', 'integer', ['signed'=>false, 'default' => 0])
			->create();
		}
	}
