<?php
/**
 * Class to work with
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright © Andrii Biriev, a@konservs.com, www.konservs.com
 */

bimport('cms.singleton');
bimport('cms.language');
bimport('items.list');
bimport('items.general');
bimport('media.server');

/**
 * Class BMediaServers
 *
 * @method BMediaServer item_get(integer $id)
 * @method BMediaServer[] items_get(integer[] $ids)
 * @method BMediaServer[] items_filter($params)
 */
class BMediaServers extends BItemsList {
	use BSingleton;
	protected $itemclassname = 'BMediaServer';
	protected $tablename = 'media_servers';

	/**
	 *
	 */
	public function items_filter_sql($params,&$wh,&$jn){
		parent::items_filter_sql($params,$wh,$jn);
		if(!empty($params['status'])){
			$wh[]='(`status` = "'.$params['status'].'")';
			}
		return true;
		}
	}
