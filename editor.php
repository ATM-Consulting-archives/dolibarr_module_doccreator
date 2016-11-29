<?php

	require 'config.php';

	$file = 'facture1.html';
	$module = 'invoice';
	
	$bodyhtml = GETPOST('bodyhtml');
	$action = GETPOST('action');
	
	$filename = DOL_DATA_ROOT.'/doccreator/'.$module.'/'.$file; //TODO secure access

	if(GETPOST('bt_generate')!='') {
		
		try {
			
			if(!class_exists('Wkhtmltopdf')) dol_include_once('/doccreator/lib/Wkhtmltopdf.php');
			
	        $wkhtmltopdf = new Wkhtmltopdf(array('path' => sys_get_temp_dir()));
			
	        $wkhtmltopdf->setTitle($dash->title);
			$wkhtmltopdf->setOrientation('landscape'); //TODO config
	        $wkhtmltopdf->setUrl($filename);
			$wkhtmltopdf->_bin = !empty($conf->global->ABRICOT_WKHTMLTOPDF_CMD) ? $conf->global->ABRICOT_WKHTMLTOPDF_CMD : 'wkhtmltopdf';
	        $wkhtmltopdf->output(Wkhtmltopdf::MODE_DOWNLOAD,$file.'.pdf');
			
	    } catch (Exception $e) {
	        echo $e->getMessage();
	    }
		
	}
	else if($action == 'save') {
		
		file_put_contents($filename, $bodyhtml);
		
		setEventMessage($langs->trans('DocSaved'));
		
		
			
	}
	
	
	$html = file_get_contents($filename);
	
	
	
	llxHeader();
	
	$formCore=new TFormCore('auto','formEdit','post');
	echo $formCore->hidden('action', 'save');
	echo $formCore->hidden('file', $file);
	echo $formCore->hidden('module', $module);
	
	echo $formCore->zonetexte('', 'bodyhtml', $html, 100,10);
	
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
				height: 200,
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
	
	
	
	


