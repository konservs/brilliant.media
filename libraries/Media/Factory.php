<?php
/**
 * Brilliant media factory, main factory class
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright © Andrii Biriev, a@konservs.com, www.konservs.com
 */

bimport('log.general');

class BMediaFactory{
	/**
	 *
	 */
	public static function parseParam($param){
		$result=array();
		$result['error']=-1;
		$result['errorMessage']='';
		
		//Check size query...
		if(strlen($param)<2){
			$result['error']=1;
			$result['errorMessage']='Size Query ('.$param.') wrong length!';
			return $result;
			}
		$csize=$param[0];
		//Check size query...
		if((strlen($param)<2)&&($csize!='g')){
			$result['error']=2;
			$result['errorMessage']='Size Query ('.$param.') wrong param length!';
			return $result;
			}
		//
		if(!(($csize=='g')||($csize=='s')||($csize=='w')||($csize=='h')||($csize=='r')||($csize=='o'))){
			die($this->debug_error('Size Query ('.$param.') wrong symbol!'));
			}
		$result['csize'] = $csize;
		//
		$sizes_string=substr($param,1);
		//
		if(($csize=='r')||($csize=='o')){
			$sizes=explode('x',$sizes_string);
			if((count($sizes)==2)&&(is_numeric($sizes[0]))&&(is_numeric($sizes[1]))){
				$result['dsize'] = (int)$sizes[0];
				$result['ysize'] = (int)$sizes[1];
				return $result;
				}
			$result['error']=3;
			$result['errorMessage']='Wrong sizes string ('.$param.')!';
			return $result;
			}
		elseif($csize=='g'){
			$result['dsize'] = 0;
			$result['ysize'] = 0;
			$result['error'] = 0;
			$result['errorMessage'] = 'all is ok ('.$param.')!';
			return $result;
			}
		$dsize=substr($param,1);
		if((!is_numeric($dsize))||((int)$dsize<=0)){
			$this->debug_error('Size Query wrong size ('.$dsize.')!');
			die();
			}
		$result['dsize'] = (int)$dsize;
		$result['ysize'] = 0;
		$result['error'] = 0;
		$result['errorMessage']='All is ok ('.$param.')!';
		return $result;
		}
	}