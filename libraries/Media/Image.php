<?php
/**
 * Class to work with media image
 *
 * @author Andrii Biriev
 *
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright © Andrii Biriev, a@konservs.com, www.konservs.com
 */

namespace Brilliant\Media;
//use Brilliant\Language;
use Brilliant\Media\Images;
use Brilliant\Media\Servers;

class BMediaImage extends BItemsItem {
	protected $collectionname = 'BMediaImages';
	protected $tablename = 'media_images';
	/**
	 * Constructor - init fields...
	 */
	function __construct() {
		parent::__construct();
		$this->fieldAddRaw('origname', 'string');
		$this->fieldAddRaw('imgtype', 'enum', array('values' => array('png', 'jpg', 'gif')));
		$this->fieldAddRaw('name', 'string');
		//Servers
		$this->fieldAddRaw('srv1', 'int', array('emptynull'=>true));
		$this->fieldAddRaw('srv2', 'int', array('emptynull'=>true));
		$this->fieldAddRaw('srv3', 'int', array('emptynull'=>true));
		//File params
		$this->fieldAddRaw('size', 'int');
		$this->fieldAddRaw('width', 'int');
		$this->fieldAddRaw('height', 'int');
		$this->fieldAddRaw('dominantcolor', 'string');
		$this->fieldAddRaw('author', 'int', array('emptynull'=>true));
		//
		$this->fieldAddRaw('hash_sha1', 'binary');
		$this->fieldAddRaw('hash_sha512', 'binary');
		//Created & modified
		$this->fieldAddRaw('created', 'dt', array('readonly' => true));
		$this->fieldAddRaw('modified', 'dt', array('readonly' => true));
		}
	/**
	 *
	 */
	public static function createFromUploadedFile($mediaServer, $authorId, $tmpName, $origname){
		//
		$pi=pathinfo($origname);
		$extention=$pi['extension'];
		//
		$format=strtolower($extention);
		if($format=='jpeg'){
			$format='jpg';
			}
		if(($format!='png')&&($format!='jpg')&&($format!='gif')){
			return false;
			}
		//Create media directory...
		$mediaImage = new BMediaImage();
		$mediaImage->author = $authorId;
		$mediaImage->origname = $origname;
		$mediaImage->imgtype = $format;
		$mediaImage->srv1 = $mediaServer->id;
		$mediaImage->srv2 = NULL;
		$mediaImage->srv3 = NULL;
		$mediaImage->created = new DateTime();
		$r = $mediaImage->createMediaDir();
		//
		$randomName = BFactory::generateRandomString(20);
		$name = $mediaImage->getMediaPath() . $randomName . '.' . $format;
		$fullName = $mediaImage->getMediaDir() . $randomName . '.' . $format;
		//Move Uploaded File
		if(!move_uploaded_file($tmpName, $fullName)){
			return false;
			}
		$params = BImages::getImageParams($fullName,$extention);
		//
		$mediaImage->name = $name;
		$mediaImage->size = filesize($fullName);
		$mediaImage->width = $params['width'];
		$mediaImage->height = $params['height'];
		$mediaImage->hash_sha1 = hash_file('sha1',$fullName,false);
		$mediaImage->hash_sha512 = hash_file('sha512',$fullName,false);
		$r = $mediaImage->savetodb();
		if(empty($r)){
			return false;
			}
		return $mediaImage;
		}
	/**
	 *
	 */
	public function getMediaServer($ind = 1){
		$mediaServerId = NULL;
		switch($ind){
			case 1:
				$mediaServerId = $this->srv1;
				break;
			case 2:
				$mediaServerId = $this->srv2;
				break;
			case 3:
				$mediaServerId = $this->srv3;
				break;
			}
		if(empty($mediaServerId)){
			return NULL;
			}
		$bMediaServers = BMediaServers::getInstance();
		$mediaServer = $bMediaServers->itemGet($mediaServerId);
		return $mediaServer;
		}
	/**
	 * Create media folder
	 * /blogs/article/<year>/<month>/<day>/<article id>/
	 *
	 */
	public function createMediaDir($serverIndex = 1){
		$dir=$this->getMediaDir($serverIndex);
		if(empty($dir)){
			return false;
			}
		if(!is_dir($dir)){
			mkdir($dir,0777, true);
			}
		return true;
		}
	/**
	 * Get media folder
	 * /<year>/<month>/<day>/
	 */
	public function getMediaDir($serverIndex = 1){
		if(empty($this->created)){
			return false;
			}
		$mediaServer = $this->getMediaServer($serverIndex);
		if(empty($mediaServer)){
			return false;
			}

		$DS=DIRECTORY_SEPARATOR;
		$dir=$mediaServer->path;
		$dir.=$this->created->format('Y').$DS;
		$dir.=$this->created->format('m').$DS;
		$dir.=$this->created->format('d').$DS;
		return $dir;
		}
	/**
	 * Get media path
	 * /<year>/<month>/<day>/<name>.jpg
	 */
	public function getMediaPath(){
		if(empty($this->created)){
			return false;
			}
		$imgpath='';
		$imgpath.=$this->created->format('Y').'/';
		$imgpath.=$this->created->format('m').'/';
		$imgpath.=$this->created->format('d').'/';
		return $imgpath;
		}
	/**
	 *
	 */
	public function getResizedWidth($param){
		if(is_array($param)){
			$param = reset($param);
			}
		bimport('media.factory');
		$exParams = BMediaFactory::parseParam($param);
		switch($exParams['csize']){
			case 'g':
				return $this->width;
			case 's':
				return $exParams['dsize'];
			case 'w':
				return $exParams['dsize'];
			case 'h':
				$div = (double)$this->height / (double)$exParams['dsize'];
				$newWidth = $this->width / $div;
				return $newWidth;
			case 'r':
				return (int)$exParams['dsize'];
			}
		throw new Exception('Unknown csize: '.$exParams['csize']);
		}
	/**
	 *
	 */
	public function getResizedHeight($param){
		if(is_array($param)){
			$param = reset($param);
			}
		bimport('media.factory');
		$exParams = BMediaFactory::parseParam($param);
		switch($exParams['csize']){
			case 'g':
				return $this->width;
			case 's':
				return $exParams['dsize'];
			case 'w':
				$div = (double)$this->width / (double)$exParams['dsize'];
				$newHeight = $this->height / $div;
				return $newHeight;
			case 'h':
				return $exParams['dsize'];
			case 'r':
				return (int)$exParams['ysize'];
			}
		throw new Exception('Unknown csize: '.$exParams['csize']);
		}
	/**
	 *
	 */
	public function getUrl($param){
		$serverIndex=empty($exparams['serverIndex'])?1:$exparams['serverIndex'];
		$mediaServer = $this->getMediaServer($serverIndex);
		if(empty($mediaServer)){
			return '';
			}
		if(!is_string($this->name)){
			return '';
			}
		$pi=pathinfo($this->name);     
		$src='';
		$srcset=array();
		if(is_array($param)){
			$param1=reset($param);
			$src=$mediaServer->url.$pi['dirname'].'/'.$pi['filename'].'.'.$param1.'.'.$pi['extension'].(!empty($exparams['uniq_image'])?('?'.hash('md5',rand(),false)):'');
			}else{
			$src=$protocol.$mediaServer->url.$pi['dirname'].'/'.$pi['filename'].'.'.$param.'.'.$pi['extension'].
			(!empty($exparams['uniq_image'])?('?'.hash('md5',rand(),false)):'');
			}
		return $src;
		}
	/**
	 *
	 */
	public function drawImage($param,$alt,$exparams=array()){
		//
		$serverIndex=empty($exparams['serverIndex'])?1:$exparams['serverIndex'];
		$mediaServer = $this->getMediaServer($serverIndex);
		if(empty($mediaServer)){
			return '';
			}
		if(!is_string($this->name)){
			return '';
			}
		$pi=pathinfo($this->name);     
		$src='';
		$srcset=array();
		if(is_array($param)){
			$param1=reset($param);
			$src=$mediaServer->url.$pi['dirname'].'/'.$pi['filename'].'.'.$param1.'.'.$pi['extension'].(!empty($exparams['uniq_image'])?('?'.hash('md5',rand(),false)):'');
			foreach($param as $k=>$p){
				$src2=$mediaServer->url.$pi['dirname'].'/'.$pi['filename'].'.'.$p.'.'.$pi['extension'].(!empty($exparams['uniq_image'])?('?'.hash('md5',rand(),false)):'');
				$srcset[]=$src2.' '.$k;
				}
			}else{
			$src=$protocol.$mediaServer->url.$pi['dirname'].'/'.$pi['filename'].'.'.$param.'.'.$pi['extension'].
			(!empty($exparams['uniq_image'])?('?'.hash('md5',rand(),false)):'');
			}
		$html='<img alt="'.htmlspecialchars($alt).'" src="'.$src.'"';
		//
		if(!empty($srcset)){
			$html.=' srcset="'.implode(',',$srcset).'"';
			}
		//
		$skipsize = isset($exparams['skipsize'])?$exparams['skipsize']:false;
		if(!$skipsize){
			$width = $this->getResizedWidth($param);
			$height = $this->getResizedHeight($param);
			$exparams['width'] = round($width,0);
			$exparams['height'] = round($height,0);
			}
		if(!empty($this->dominantcolor)){
			if(empty($exparams['style'])){
				$exparams['style']='';
				}
			$exparams['style'] .= 'background-color: #'.$this->dominantcolor;
			}
		//Other params
		foreach($exparams as $k=>$v){
			$html.=' '.$k.'="'.htmlspecialchars($v).'"';
			}
		$html.='>';
		return $html;
		}
	}
