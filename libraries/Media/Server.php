<?php
/**
 * Class to work with media server
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright © Andrii Biriev, a@konservs.com, www.konservs.com
 */


class BMediaServer extends BItemsItem {
	protected $collectionname = 'BMediaServers';
	protected $tablename = 'media_servers';
	/**
	 * Constructor - init fields...
	 */
	function __construct() {
		parent::__construct();
		$this->fieldAddRaw('name', 'string');
		$this->fieldAddRaw('status', 'enum', array('values' => array('O', 'D')));
		$this->fieldAddRaw('url', 'string');
		$this->fieldAddRaw('path', 'string');
		//
		$this->fieldAddRaw('bytesuploaded', 'string', array('readonly' => true));
		$this->fieldAddRaw('filesuploaded', 'string', array('readonly' => true));
		$this->fieldAddRaw('created', 'dt', array('readonly' => true));
		$this->fieldAddRaw('modified', 'dt', array('readonly' => true));
		}
	}
