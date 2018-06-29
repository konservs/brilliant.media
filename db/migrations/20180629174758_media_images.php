<?php
use Phinx\Migration\AbstractMigration;

class MediaImages extends AbstractMigration{
	/**
	 *
	 */
	public function change(){
		$table = $this->table('media_images',array('id' => false, 'primary_key' => array('id')));
		$table->addColumn('id', 'integer', ['signed'=>false, 'identity'=>true])
			->addColumn('origname', 'char', ['length'=>150])
			->addColumn('imgtype', 'enum', ['values'=>['jpg','png','gif'],'default'=>'jpg'])
			->addColumn('name', 'char', ['length'=>150])


			->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
			->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
			->create();
		}
}
