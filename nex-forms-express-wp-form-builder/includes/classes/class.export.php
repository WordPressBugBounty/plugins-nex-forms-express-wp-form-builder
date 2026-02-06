<?php
if ( ! defined( 'ABSPATH' ) ) exit;



add_action('init', 'NEXForms_do_form_export');
function NEXForms_do_form_export() {
	

	$export_form = isset($_REQUEST['export_form']) ? sanitize_text_field($_REQUEST['export_form']) : false;
	if($export_form)
		{
		if(!current_user_can( 'activate_plugins' ))	
			wp_die();
		else
			$formExport = new NF5_Export_Forms();
		}

}



if(!class_exists('NF5_Export_Forms'))
	{
	class NF5_Export_Forms
		{
		/**
		* Constructor
		*/
		public function __construct(){
			
			$export_form = isset($_REQUEST['export_form']) ? sanitize_text_field($_REQUEST['export_form']) : '';
			
			$db_actions = new NEXForms_Database_Actions();
			if($export_form)
				{
				$form_export = $this->generate_form();
				if($form_export)
					{
					header("Pragma: public");
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: private", false);
					//header("content-type:application/csv;charset=UTF-8");
					header("Content-Disposition: attachment; filename=\"".$db_actions->get_title2(sanitize_title($_REQUEST['nex_forms_Id']),'wap_nex_forms').".txt\";" );
					//header("Content-Transfer-Encoding: base64");
					
					echo $form_export; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				exit;
				}
		}	
		/**
		* Converting data to HTML
		*/
		public function generate_form(){
			global $wpdb;
				
				if(!current_user_can( 'activate_plugins' ))	
					return false;
				else
					{
					$form_data = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d ',sanitize_title($_REQUEST['nex_forms_Id']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					//$content = str_replace('\\','',$form_data->form_fields);
					$content = '';
					$fields 	= $wpdb->get_results("SHOW FIELDS FROM " . $wpdb->prefix ."wap_nex_forms"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$field_array = array();
					$count_fields = count($fields);
					$i = 0;
					
					$insert_array = array();
					$content .= '(';
					foreach($fields as $field)
						{
						if($field->Field!='date_sent')
							{
							$content .= '`'.$field->Field.'`'.(($i<$count_fields-2) ? ',' : '').'';
							 $my_fields[$field->Field]=$field->Field;
							 $i++;
							}
						}
					$content .= ') VALUES (';
					
					$j = 0;
					
					
					foreach($my_fields as $key=>$value)
						{
						$insert_array['Id'] =  'NULL';
						if($key!='date_sent' || $key!='Id')
							{
							$set_value = str_replace('\\','',$form_data->$value);
							$set_value = str_replace('\'','',$set_value);
							
							$insert_array[$key] =  $set_value; 
							
							$j++;
							}
						}
					
					
					$content .= ')';
					
					return json_encode($insert_array, JSON_UNESCAPED_UNICODE);
					}
			}
		}
	}
