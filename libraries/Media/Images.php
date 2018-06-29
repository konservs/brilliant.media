<?php
/**
 * Class to work with Media images
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright © Andrii Biriev, a@konservs.com, www.konservs.com
 */

namespace Brilliant\Media;
//use Brilliant\Language;
use Brilliant\Media\Image;

/**
 * Class BMediaImages
 *
 * @method Brilliant\Media\Image itemGet(integer $id)
 * @method Brilliant\Media\Image[] itemsGet(integer[] $ids)
 * @method Brilliant\Media\Image[] itemsFilter($params)
 */
class BMediaImages extends BItemsList {
	use \Brilliant\BSingleton;
	protected $itemclassname = '\Brilliant\Media\Image';
	protected $tablename = 'media_images';
	}
