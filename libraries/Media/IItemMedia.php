<?php
/**
 * Interface for items to easilly store media stuff for easy backup.
 *
 * @author Andrii Biriev
 *
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright © Andrii Biriev, a@konservs.com, www.konservs.com
 */

namespace Brilliant\Media;

interface IItemMedia {
	protected $mediapath = [];

	/**
	 * Create media folder
	 * /classified/offers/<year>/<month>/<day>/<article id>/
	 *
	 */
	public function createmediadir(){
		$itemsmediadir=$this->getmediadir();
		if(empty($itemsmediadir)){
			return false;
			}
		if(!is_dir($itemsmediadir)){
			mkdir($itemsmediadir,0777, true);
			}
		return true;
		}
	/**
	 * Get media folder. For example:
	 * /news/articles/<year>/<month>/<day>/<article id>/
	 */
	public function getmediadir(){
		if(empty($this->created)){
			return false;
			}
		if(empty($this->id)){
			return false;
			}
		$DS=DIRECTORY_SEPARATOR;
		$itemsmediadir=MEDIA_PATH_ORIGINAL.$DS;
		foreach ($this->mediapath as $pi) {
			$itemsmediadir.=$pi.$DS;
		}
		$itemsmediadir.=$this->created->format('Y').$DS;
		$itemsmediadir.=$this->created->format('m').$DS;
		$itemsmediadir.=$this->created->format('d').$DS;
		$itemsmediadir.=$this->id;
		return $itemsmediadir;
		}
	/**
	 * Get media path
	 * /news/articles/<year>/<month>/<day>/<article id>/
	 */
	public function getmediapath(){
		if(empty($this->created)){
			return false;
			}
		if(empty($this->id)){
			return false;
			}
		$itemsmediapath='/';
		foreach ($this->mediapath as $pi) {
			$itemsmediapath.=$pi.'/';
		}
		$itemsmediapath.=$this->created->format('Y').'/';
		$itemsmediapath.=$this->created->format('m').'/';
		$itemsmediapath.=$this->created->format('d').'/';
		$itemsmediapath.=$this->id;
		return $itemsmediapath;
		}
	}
