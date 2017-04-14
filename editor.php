<?php

	require 'config.php';

	$model = 'facture1.html';
	$module = 'invoice';
	
	$bodyhtml = GETPOST('bodyhtml');
	$action = GETPOST('action');
	
	$filename = DOL_DATA_ROOT.'/doccreator/'.$module.'/'.$model; //TODO secure access
	$fileparam = DOL_DATA_ROOT.'/doccreator/'.$module.'/param.json';
	
	$TParam = unserialize(file_get_contents($fileparam));
	
	if(GETPOST('bt_generate')!='') {
		
		$file = basename($filename);
		
		try {
			
			$wkhtmltopdf = new Wkhtmltopdf(array(
					'path' => sys_get_temp_dir(),'margin-left'=>0,
					'margin-right'=>0,
					'margin-top'=>0,
					'margin-bottom'=>0,
					'print-media-type',
					'disable-smart-shrinking',
					
			));
			
			$wkhtmltopdf->setOptions(array('encoding'=>'iso-8859-1'));
			
			//var_dump($TParam,$file);
	        $wkhtmltopdf->setTitle($TParam[$file]['title']);
			$wkhtmltopdf->setOrientation($TParam[$file]['orientation']); //TODO config
	        $wkhtmltopdf->setUrl($filename);
			$wkhtmltopdf->_bin = !empty($conf->global->ABRICOT_WKHTMLTOPDF_CMD) ? $conf->global->ABRICOT_WKHTMLTOPDF_CMD : 'wkhtmltopdf';
			$wkhtmltopdf->output(Wkhtmltopdf::MODE_DOWNLOAD,$model.'.pdf');
			
	    } catch (Exception $e) {
	        echo $e->getMessage();
	    }
		
	}
	else if($action == 'save') {
		
		file_put_contents($filename, $bodyhtml);
		
		$TParam[$model]=array(
			'orientation'=>GETPOST('orientation')
			,'title'=>GETPOST('title')
		);
		file_put_contents($fileparam, serialize($TParam));
		
		setEventMessage($langs->trans('DocSaved'));
		
		
			
	}
	
	
	$html = file_get_contents($filename);
	
	
	
	llxHeader();
	
	$formCore=new TFormCore('auto','formEdit','post');
	echo $formCore->hidden('action', 'save');
	echo $formCore->hidden('model', $model);
	echo $formCore->hidden('module', $module);
	echo $formCore->texte('Title', 'title', $TParam[$model]['title'], 30, 255);
	echo $formCore->combo('Orientation', 'orientation', array('landscape'=>$langs->trans('Landscape'),'portrait'=>$langs->trans('Portrait') ), $TParam[$model]['orientation']);
	
	echo $formCore->zonetexte('', 'bodyhtml', $html, 100,20);
	
	echo $formCore->btsubmit($langs->trans('Save'), 'bt_save');
	echo $formCore->btsubmit($langs->trans('GenerateTestPDF'), 'bt_generate');
	
	$formCore->end();
	
?>
<script type="text/javascript">
	$(document).ready(function () {
        /* if (CKEDITOR.loadFullCore) CKEDITOR.loadFullCore(); */
        /* should be editor=CKEDITOR.replace but what if serveral editors ? */
        CKEDITOR.replace('bodyhtml',
			{
				/* property:xxx is same than CKEDITOR.config.property = xxx */
				customConfig : ckeditorConfig,
				readOnly : false,
        		htmlEncodeOutput :false,
				allowedContent :false,
				extraAllowedContent : '',
				fullPage : false, 
        		toolbar: 'dolibarr_notes',
				toolbarStartupExpanded: true,
				width: '',
				height: 400,
                skin: 'moono',
                language: 'fr_FR',
                textDirection: 'ltr',
                on :
                        {
                            instanceReady : function( ev )
                            {
                                // Output paragraphs as <p>Text</p>.
                                this.dataProcessor.writer.setRules( 'p',
                                                    {
                                                        indent : false,
                                                        breakBeforeOpen : true,
                                                        breakAfterOpen : false,
                                                        breakBeforeClose : false,
                                                        breakAfterClose : true
                                                    });
                                            }
                                        },
			filebrowserBrowseUrl : ckeditorFilebrowserBrowseUrl,    filebrowserImageBrowseUrl : ckeditorFilebrowserImageBrowseUrl,
			filebrowserWindowWidth : '900',
           filebrowserWindowHeight : '500',
           filebrowserImageWindowWidth : '900',
           filebrowserImageWindowHeight : '500'	})});
	</script>
<?php
	
	llxFooter();	
	
	
	
	


