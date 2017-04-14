<?php

class DocCreator {
	
	static function getModelList($module) {
		
		dol_include_once('/core/lib/files.lib.php');
		
		$TDir = dol_dir_list(DOL_DATA_ROOT.'/doccreator/'.$module);
		
		$TList=array('param'=>array(),'files'=>array());
		
		foreach($TDir as &$dir) {
			
			if($dir['name']==='param.json') {
				
				$TList['param'] = unserialize(file_get_contents($dir['fullname']));
				
			}
			else{
				
				$TList['files'][] = $dir;
				
			}
		}
		
		foreach ($TList['files'] as &$file) {
			
			$param = $TList['param'][$file['name']];
			
			$file = array_merge($file,$param);
			
		}
		
		
		return $TList;
	}
	
	static function convertField(&$object) {
		
		foreach($object as $k=>$v) {
			
			if(strpos($k,'date')===0 && is_int($v)) {
				
				$object->{$k.'_fr'} = dol_print_date($v);
				
			}
			
			
		}
		
		
	}
	
	
}
