<?php

class DocCreator {
	
	static function convertField(&$object) {
		
		foreach($object as $k=>$v) {
			
			if(strpos($k,'date')===0 && is_int($v)) {
				
				$object->{$k.'_fr'} = dol_print_date($v);
				
			}
			
			
		}
		
		
	}
	
	
}
