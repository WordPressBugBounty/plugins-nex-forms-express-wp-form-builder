<?php
if ( ! defined( 'ABSPATH' ) ) exit;


function NEXForms_entries_page(){
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	$nf_function = new NEXForms_functions();
	
	$database_actions = new NEXForms_Database_Actions();
	
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	
	
	
	$count_entries = $wpdb->get_results('SELECT nex_forms_Id, COUNT(nex_forms_Id) as counted FROM `'.$wpdb->prefix.'wap_nex_forms_entries` WHERE trashed IS NULL GROUP BY nex_forms_Id;'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			foreach($count_entries as $entry)
				{
				$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms', array('entry_count'=>$entry->counted), array('Id' => $entry->nex_forms_Id) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
	
	
	
	$entries = new NEXForms_dashboard();
	//$entries->action = 'print_entries';
	
	
	
	
	
	$entries->table = 'wap_nex_forms_entries';
	$entries->table_resize = true;
	$entries->table_header = '<span class="fas fa-filter"></span>&nbsp;&nbsp;Filters:';
	$entries->extra_buttons = array(
								'unread'		=>array('class'=>'filter_unread filter_button', 		'id'=>'filter_unread', 		'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="fas fa-eye-slash"></span> '.__('&nbsp;Unread','nex-forms').''),
								'starred'		=>array('class'=>'filter_starred filter_button', 		'id'=>'filter_starred', 	'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="fas fa-star"></span> '.__('&nbsp;Starred','nex-forms').''),
								'attachments'	=>array('class'=>'filter_attachments filter_button', 	'id'=>'filter_attachments', 'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="fas fa-paperclip"></span> '.__('&nbsp;Attachment','nex-forms').'')
								//'read'			=>array('class'=>'filter_read filter_button', 			'id'=>'filter_read', 		'type'=>'button','link'=>'', 'icon'=>'<span class="fas fa-eye"></span> '.__('&nbsp;Read','nex-forms').''),
								);
	$entries->table_header_icon = 'assignment';
	$entries->additional_params = array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
	$entries->table_headings = array(
	'Id',
	array('icon'=>'fas fa-star', 'user_func'=>'NEXForms_starred','user_func_args_1'=>'Id', 'user_func_args_2'=>'wap_nex_forms_entries', 'set_class'=>'custom starred','sort_by'=>'starred'),
	array('icon'=>'fas fa-paperclip', 'user_func'=>'NEXForms_get_attachment','user_func_args_1'=>'Id', 'user_func_args_2'=>'wap_nex_forms_files', 'set_class'=>'custom read', 'sort_by'=>'attachments'),
	array('icon'=>'fas fa-glasses', 'user_func'=>'NEXForms_entry_status','user_func_args_1'=>'Id', 'user_func_args_2'=>'wap_nex_forms_entries', 'set_class'=>'custom read','sort_by'=>'viewed'),
	//'title',
	array('heading'=> __('Form','nex-forms'), 'user_func'=>'NEXForms_get_title3','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms','sort_by'=>'nex_forms_Id'),
	'page',
	array('heading'=>__('Submitted','nex-forms'), 'user_func'=>'NEXForms_time_elapsed_string','user_func_args_1'=>'date_time', 'user_func_args_2'=>'wap_nex_forms', 'sort_by'=>'date_time'),
	'date_time',
	);
	$entries->show_headings=true;
	$entries->search_params = array('Id','form_data');
	$entries->color_adapt = true;
	$entries->checkout = $dashboard->checkout;
	$entries->record_limit = 100;
	$entries->show_delete  = false;
	
	
	
	
	/*$file_uploads = new NEXForms_dashboard();
	$file_uploads->table = 'wap_nex_forms_files';
	$file_uploads->table_header = 'Form Entries';
	$file_uploads->table_header_icon = 'insert_drive_file';
	$file_uploads->table_headings = array('entry_Id', array('heading'=>__('Form','nex-forms'), 'user_func'=>'NEXForms_get_title','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'), 'name','type','size','url');
	$file_uploads->show_headings=true;
	$file_uploads->extra_classes = 'file_manager';
	$file_uploads->search_params = array('entry_Id','name','type');
	//$file_uploads->build_table_dropdown = 'form_id';
	$file_uploads->checkout = $dashboard->checkout;
	$file_uploads->show_delete  = true;*/
	
	
	$output .= '<div class="hidden" style="display:none;">';
	$output .= $dashboard->dashboard_menu('Form Entries');
	$output .= '</div>';
	
	
	$output .= '<div class="admin_url" style="display:none;">'.admin_url().'</div>';
	
	$nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
	$output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';
		
	
	$output .= '<div class="nf_context_menu nf_context_menu_2 aa_menu aa_bg_main">
				
				<ul class="aa_menu">
					<li class="cm-action-item mark-read" data-action="mark-read"><a href="#" class="cm-item-text"><span class="fas fa-eye"></span>Mark as read</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item mark-unread" data-action="mark-unread"><a href="#" class="cm-item-text"><span class="fas fa-eye-slash"></span>Mark as unread</span><span class="kbsc"></span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item" data-action="add-star"><a href="#" class="cm-item-text"><span class="fas fa-star"></span> Add Star</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item" data-action="remove-star"><a href="#" class="cm-item-text"><span class="far fa-star"></span> remove Star</span><span class="kbsc"></span></a></li>
					
					<li class="sec_divider restore_record" tyle="display:none;"></li>
					<li class="cm-action-item restore_record" data-action="restore" style="display:none;"><a href="#" class="cm-item-text"><span class="fas fa-trash-restore"></span> Restore</span><span class="kbsc"></span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item" data-action="delete"><a href="#" class="cm-item-text"><span class="fas fa-trash"></span> Delete</span><span class="kbsc">Delete</span></a></li>
				<ul>
			</div>';
	
	$output .= '<div id="dashboard_panel" class="dashboard_panel">';
		  	  
			  
			
			  
			  
			  
			 
			  
			  
			  $output .= $dashboard->new_menu();
	
	$output .= '<div id="nex_forms_entries" class="nex_forms_entries">';
		
		$output .= '<div class="entries_wrapper">';
			
			$output .= '<div class="left-col aa_bg_main">';
					
				$output .= $dashboard->entries_menu();
				
			$output .= '</div>';
			
			$output .= '<div class="right-col">';
				$output .= '<div class="right-col-top">';
					$output .= $entries->print_record_table();
					
					
					
				$output .= '</div>';
				$output .= '<div class="right-mid">';
					
					
					
					$output .= '<div class="entry_tools aa_bg_main">';
						
						$output .= '<button type="submit" class="save_form_entry save_button button button-primary" style="display:none;">'.__('Save','nex-forms').'</button>';
						$output .= '<button class="cancel_save_form_entry save_button button button-primary" style="display:none;"><i class="fa fa-close"></i></button>';
						
						
						$output .= '<div class="entry_views">';
						
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch  view_form_data active" data-action="view-data" disabled="disabled"><span class="fas fa-database"></span> '.__('Entry Data','nex-forms').'</button>';
							if($dashboard->checkout){
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch" data-action="view-admin-email" disabled="disabled"><span class="fas fa-envelope"></span> '.__('View Admin Email','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch" data-action="view-user-email" disabled="disabled"><span class="far fa-envelope"></span> '.__('View User Email','nex-forms').'</button>';
							}
							else
							{
							$output .= '<button class="nf_button aa_bg_main_btn  no_batch"  disabled="disabled"><span class="fas fa-envelope"></span> '.__('View Admin Email','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn  no_batch"  disabled="disabled"><span class="far fa-envelope"></span> '.__('View User Email','nex-forms').'</button>';
							}
						$output .= '</div>';
					
						$output .= '<div class="entry_actions">';	
							$output .= '<button class="print_to_pdf aa_bg_main_btn no_batch nf_button" disabled="disabled"><span class="fas fa-file-pdf"></span> '.__('Export to PDF','nex-forms').'</button>';
							//$output .= '<button class="button no_batch do_action" data-action="print-form-entry" disabled="disabled"><span class="fas fa-print"></span> '.__('Print','nex-forms').'</button>';
							$output .= '<button id="" class="edit_form_entry aa_bg_main_btn no_batch nf_button" disabled="disabled"><span class="fas fa-pen-square"></span> '.__('Edit','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action" data-action="delete" disabled="disabled"><span class="fas fa-trash"></span> '.__('Delete','nex-forms').'</button>';
						$output .= '</div>';
						
					$output .= '</div>';
					
				$output .= '</div>';
				$output .= '<div class="right-bottom">';
					$output .= $entries->print_form_entry();
				$output .= '</div>';
				
			$output .= '</div>';
			
		$output .= '</div>';
		
	
		//
		
		
		//
	$output .= '</div>';
	$output .= '</div>';
	NEXForms_clean_echo( $output);
	
	$dashboard->remove_unwanted_styles();
	
	update_option('nf_activated',$dashboard->checkout);
}

function NEXForms_stats_page(){
	global $wpdb;
	$theme = wp_get_theme();
	$nf_function = new NEXForms_functions();
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	$dashboard->color_adapt = true;
	
	
	$output = '';
	$output .= '<div class="hidden">';
		$output .= $dashboard->dashboard_menu('Form Analytics');
	$output .= '</div>';
	/*if(!$dashboard->checkout)
			{
				 $output .= '<div id="dashboard_panel" class="dashboard_panel">';
					$output .= '<div class="row row_zero_margin ">';
						
						$output .= '<div class="col-sm-5">';
							$output .= $dashboard->license_setup();
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			
			NEXForms_clean_echo( $output);
			return;
			}
	*/
	$output .= '<div id="dashboard_panel" class="dashboard_panel">';
	$output .= $dashboard->new_menu();
	
	$output .= '<div id="nex_forms_entries" class="nex_forms_entries submission_reporting analytics">';
		
		$output .= '<div class="entries_wrapper">';
			
			$output .= '<div class="left-col aa_bg_main">';
				$output .= '<div class="stat-controls">';		
				//$output .= $dashboard->analytics_menu();
				$output .= '
							<label>Select Form</label>
							<select class="form_control aa_bg_main_input" name="stats_per_form" style="display:block">';
								$output .= '<option value="0" selected>'.__('All Forms','nex-forms').'</option>';
								$get_forms = 'SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE is_template<>1 AND is_form<>"preview" AND is_form<>"draft" ORDER BY Id DESC';
								
								$forms = $wpdb->get_results($get_forms);
								foreach($forms as $form)
									$output .= '<option value="'.$form->Id.'">'.str_replace('\\','',$form->title).'</option>';
							$output .= '</select>';
				$output .= '
							<label>Year</label>
							<select class="form_control aa_bg_main_input" name="stats_per_year" style="display:block">';
								$current_year = (int)date('Y');
								$output .= '<option value="'.$current_year.'" selected>'.$current_year.'</option>';
								for($i=($current_year-1);$i>=($current_year-20);$i--)
									{
									if($i>=2015)
										$output .= '<option value="'.$i.'">'.$i.'</option>';
									}
							$output .= '</select>';
				
				$output .= '
							<label>Month</label>
							<select class="form_control aa_bg_main_input" name="stats_per_month" style="display:block">';
							$month_array = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
								$output .= '<option value="0">'.__('All Year','nex-forms').'</option>';
								$current_month = (int)date('m');
								foreach($month_array as $key=>$val)
									{
									$output .= '<option value="'.$key.'" '.(($key==$current_month) ? 'selected' : '' ).'>'.$val.'</option>';
									
									}
							$output .= '</select>';
				$output .= '</div>';
			$output .= '</div>';
			
			$output .= '<div class="right-col">';
	 			
	 
			$output .= '<div class="hidden">';
			  $output .= '<div id="siteurl">'.get_option('siteurl').'</div>';
			  $output .= '<div id="nf_dashboard_load">0</div>';
			  $output .= '<div id="plugins_url">'.plugins_url('/',__FILE__).'</div>';
			  $output .= '<div id="load_entry">'.$dashboard->checkout.'</div>';
			$output .= '</div>';
	  
				
					 
					  $output .= '<div class="form_analytics_panel">';
					  	
						$output .= '<div class="stats-title-main">';
						
						
						$output .= '<div class="head_text">'.__('Form Analytics','nex-forms').'&nbsp;-&nbsp;<span class="analytics_form">All Forms</span></div>';
						$output .= '<div class="sub_head_text"><span class="analytic_month">'.$month_array['0'.$current_month].'</span> <span class="analytic_year">'.$current_year.'</span></div>';
							
							
							
							
							
						
						
					$output .= '</div>';
						
						$output .= '<div class="stats_container">';
							
							  
							  
							  /*$output .= '<div class="row">';
									$output .= '<div  class="col-sm-9">';
										$output .= ' <div id="curve_chart" style="width: 900px; height: 500px"></div>';
									$output .= '</div>';
							  $output .= '</div>';*/
							 
							  if(!$dashboard->checkout)
							  	{
									$output .= '<div class="row"><div class="alert alert-danger"><span class="fas fa-lock"></span> PREMIUM ONLY FEATURE: An active premium license is required to view form analytical data. <a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock"" class="upgrade-link" target="_blank"> Upgrade to Premium <span class="fa-solid fa-angles-up"></span></a></div></div>';
								}
								
							if ( nf_fs()->can_use_premium_code() )
								{
								$output .= '<div class="row">';
									$output .= '<div class="col-sm-4">';
										$output .= '<div class="stats_summary_container">';
										$output .= '<div class="geo_heading">'.__('Overview','nex-forms').'</div>';
												$output .= '<div class="stats_summary_container2"><div class="row stats aa_bg_sec header_stats"><div class="col-xs-3"><span class="big_txt">0</span> <label style="cursor:default;color:#60a1e1">Form Views</label> </div><div class="col-xs-3"><span class="big_txt">0</span> <label style="cursor:default;color:#8BC34A">Form Interactions</label> </div><div class="col-xs-3"><span class="big_txt">0</span> <label style="cursor:default;color:#F57C00">Form Submissions</label> </div><div class="col-xs-3"><span class="big_txt">0.00%</span> <label>Conversion</label> </div></div></div>';	
										$output .= '</div>';
										
										
										$output .= '<div class="top_performing_form">';
										$output .= '<div class="geo_heading">'.__('Top Forms by submissions','nex-forms').'</div>';
										$output .= '<div class="top_forms_container"></div>';
										$output .= '</div>';
										
									$output .= '</div>';
								
									$output .= '<div  class="col-sm-8 analytics_panel">';
										$output .= '<div class="geo_heading">'.__('Form Events','nex-forms').'</div>';
										$output .= $dashboard->form_analytics();
									$output .= '</div>';
							  $output .= '</div>';
							  
							  
								  $output .= '<div class="row">';
										$output .= '<div class="col-sm-9 geo_panel">';
											$output .= '<div class="geo_heading">'.__('Global Form Submissions','nex-forms').'</div>';
											$output .= '<div id="regions_div" style="width: 100%;"></div>';
										$output .= '</div>';
										
										$output .= '<div class="col-sm-3">';
											$output .= '<div class="geo_heading">'.__('Top Countries','nex-forms').'</div>';
											$output .= '<div class="geo_stats_container"><ul class="top_countries"></ul></div>';
											
											
										$output .= '</div>';
										
								  $output .= '</div>';
									
								}
							else
								{
							    $license_info = $dashboard->license_info;
							
								$supported_until = $license_info['supported_until'];
								$supported_date = new DateTime($supported_until);
								$now = new DateTime();
							
							  if ($supported_date > $now)
								{
							  
							  		$output .= '<div class="col-sm-4">';
										$output .= '<div class="stats_summary_container">';
										$output .= '<div class="geo_heading">'.__('Overview','nex-forms').'</div>';
												$output .= '<div class="stats_summary_container2"><div class="row stats aa_bg_sec header_stats"><div class="col-xs-3"><span class="big_txt">0</span> <label style="cursor:default;color:#60a1e1">Form Views</label> </div><div class="col-xs-3"><span class="big_txt">0</span> <label style="cursor:default;color:#8BC34A">Form Interactions</label> </div><div class="col-xs-3"><span class="big_txt">0</span> <label style="cursor:default;color:#F57C00">Form Submissions</label> </div><div class="col-xs-3"><span class="big_txt">0.00%</span> <label>Conversion</label> </div></div></div>';	
										$output .= '</div>';
										
										
										$output .= '<div class="top_performing_form">';
										$output .= '<div class="geo_heading">'.__('Top Forms by submissions','nex-forms').'</div>';
										$output .= '<div class="top_forms_container"></div>';
										$output .= '</div>';
										
									$output .= '</div>';
								}
									$output .= '<div  class="col-sm-8 analytics_panel">';
										$output .= '<div class="geo_heading">'.__('Form Events','nex-forms').'</div>';
										$output .= $dashboard->form_analytics();
									$output .= '</div>';
							  $output .= '</div>';
							  
							  if ($supported_date > $now)
								{
								  $output .= '<div class="row">';
										$output .= '<div class="col-sm-9 geo_panel">';
											$output .= '<div class="geo_heading">'.__('Global Form Submissions','nex-forms').'</div>';
											$output .= '<div id="regions_div" style="width: 100%;"></div>';
										$output .= '</div>';
										
										$output .= '<div class="col-sm-3">';
											$output .= '<div class="geo_heading">'.__('Top Countries','nex-forms').'</div>';
											$output .= '<div class="geo_stats_container"><ul class="top_countries"></ul></div>';
											
											
										$output .= '</div>';
										
								  $output .= '</div>';
							  
								}
								}
					 		$output .= '</div>';
		  				$output .= '</div>';
		  $output .= '</div>';
		 $output .= '</div>';  	
	 $output .= '</div>'; //nex_forms_admin_page_wrapper
	$output .= '</div>';  	
	NEXForms_clean_echo( $output);
	
	update_option('nf_activated',$dashboard->checkout);
}

function NEXForms_reporting_page(){
	global $wpdb;
	$theme = wp_get_theme();

	$nf_function = new NEXForms_functions();
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();

	
	$output = '';
	
	
	$output .= '<div class="hidden">';
	$output .= $dashboard->dashboard_menu('Submission Reporting');
	$output .= '</div>';
	if(!$dashboard->checkout)
			{
				 $output .= '<div id="dashboard_panel" class="dashboard_panel">';
					$output .= '<div class="row row_zero_margin ">';
						
						$output .= '<div class="col-sm-5">';
							$output .= $dashboard->license_setup();
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
			
			NEXForms_clean_echo( $output);
			return;
			}
	
	 $output .= '<div id="dashboard_panel" class="dashboard_panel">';
		$output .= $dashboard->new_menu();
		
		
	 $output .= '<div id="nex_forms_entries" class="nex_forms_entries submission_reporting">';
		
		$output .= '<div class="entries_wrapper">';
			
			$output .= '<div class="left-col aa_bg_main">';
					
				$output .= $dashboard->reporting_menu();
				
			$output .= '</div>';
			
			$output .= '<div class="right-col">';
	 			
	 
			$output .= '<div class="hidden">';
			  $output .= '<div id="siteurl">'.get_option('siteurl').'</div>';
			  $output .= '<div id="nf_dashboard_load">0</div>';
			  $output .= '<div id="plugins_url">'.plugins_url('/',__FILE__).'</div>';
			  $output .= '<div id="load_entry">'.$dashboard->checkout.'</div>';
			$output .= '</div>';
			$nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
			$output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';	
		  //DASHBOARD
				  $output .= '<div id="" class="reporting_panel">';
			 
						$output .= '<div id="submission_reports" class="" >';
							$output .= '<div class="row row_zero_margin report_table_selection">';
								
								$output .= '<div class="col-xs-12 zero_padding">';
									$output .= '<div class="row row_zero_margin report_table_container">';
										$output .= '<div class="col-sm-12 zero_padding ">';
											$output .= '<div class="right-col">';
												
												$output .= '<div class="right-col-top faded">
																<div class="right-col-inner aa_bg_tri">
																  <div class="reporting_controls">
																	<div class="col-sm-3 field_selection_col ">
																	  <select name="showhide_fields[]" multiple="multiple" class="aa_multi_select field_selection_multi_select">
																		<option disabled="disabled">Show Fields</option>
																	  </select>
																	</div>
																	
																</div>
															  </div>
															  <div class="right-bottom">
																<div class="dashboard-box database_table wap_nex_forms_temp_report wap_nex_forms_entries" data-table="wap_nex_forms_temp_report">
																  <div class="dashboard-box-header aa_bg_main">
																	<div class="table_title font_color_1 ">Report</div>
																	
																	  </div>
																  <div class="dashboard-box-content zero_padding">
																	<div class="no_records"><span class="fa fa-ban"></span> <span class="result_text">No results found</span></div>
																	
																  </div>
																  
																</div>
															  </div>';
													
												
												$output .= '</div>';					
											
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						  $output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
		
		 $output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
	
	$output .= '</div>';
		$output .= '</div>';
	
	NEXForms_clean_echo( $output);
	
	$dashboard->remove_unwanted_styles();
	
	update_option('nf_activated',$dashboard->checkout);
}

function NEXForms_attachments_page(){
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	$nf_function = new NEXForms_functions();
	
	$database_actions = new NEXForms_Database_Actions();
	
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	
	/*$entries = new NEXForms_dashboard();
	//$entries->action = 'print_entries';
	$entries->table = 'wap_nex_forms_entries';
	$entries->table_resize = true;
	$entries->table_header = '<span class="fas fa-filter"></span>&nbsp;&nbsp;Filters:';
	$entries->extra_buttons = array(
								'unread'		=>array('class'=>'filter_unread filter_button', 		'id'=>'filter_unread', 		'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="fas fa-eye-slash"></span> '.__('&nbsp;Unread','nex-forms').''),
								'starred'		=>array('class'=>'filter_starred filter_button', 		'id'=>'filter_starred', 	'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="fas fa-star"></span> '.__('&nbsp;Starred','nex-forms').''),
								'attachments'	=>array('class'=>'filter_attachments filter_button', 	'id'=>'filter_attachments', 'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="fas fa-paperclip"></span> '.__('&nbsp;Attachment','nex-forms').'')
								//'read'			=>array('class'=>'filter_read filter_button', 			'id'=>'filter_read', 		'type'=>'button','link'=>'', 'icon'=>'<span class="fas fa-eye"></span> '.__('&nbsp;Read','nex-forms').''),
								);
	$entries->table_header_icon = 'assignment';
	$entries->additional_params = array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
	$entries->table_headings = array(
	'Id',
	array('icon'=>'fas fa-star', 'user_func'=>'NEXForms_starred','user_func_args_1'=>'Id', 'user_func_args_2'=>'wap_nex_forms_entries', 'set_class'=>'custom starred','sort_by'=>'starred'),
	array('icon'=>'fas fa-paperclip', 'user_func'=>'NEXForms_get_attachment','user_func_args_1'=>'Id', 'user_func_args_2'=>'wap_nex_forms_files', 'set_class'=>'custom read', 'sort_by'=>'attachments'),
	array('icon'=>'fas fa-glasses', 'user_func'=>'NEXForms_entry_status','user_func_args_1'=>'Id', 'user_func_args_2'=>'wap_nex_forms_entries', 'set_class'=>'custom read','sort_by'=>'viewed'),
	//'title',
	array('heading'=> __('Form','nex-forms'), 'user_func'=>'NEXForms_get_title3','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'),//'sort_by'=>'nex_forms_Id'
	'page',
	array('heading'=>__('Submitted','nex-forms'), 'user_func'=>'NEXForms_time_elapsed_string','user_func_args_1'=>'date_time', 'user_func_args_2'=>'wap_nex_forms', 'sort_by'=>'date_time'),
	'date_time',
	);
	$entries->show_headings=true;
	$entries->search_params = array('form_data');
	$entries->color_adapt = true;
	$entries->checkout = $dashboard->checkout;
	$entries->record_limit = 100;
	$entries->show_delete  = false;*/
	
	
	
	
	$file_uploads = new NEXForms_dashboard();
	$file_uploads->table = 'wap_nex_forms_files';
	$file_uploads->table_header = '';
	$file_uploads->table_header_icon = 'insert_drive_file';
	$file_uploads->table_headings = array('Id','entry_Id', array('heading'=>__('Form','nex-forms'), 'user_func'=>'NEXForms_get_title','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'), 'name','type','size','url', array('heading'=>'', 'user_func'=>'NEXForms_download_file','user_func_args_1'=>'url','user_func_args_2'=>'wap_nex_forms_files','set_class'=>'read download'));
	$file_uploads->show_headings=true;
	$file_uploads->table_resize = true;
	$file_uploads->extra_classes = ' wap_nex_forms_entries file_manager';
	$file_uploads->search_params = array('Id','entry_Id','name','type');
	$file_uploads->color_adapt = true;
	//$file_uploads->build_table_dropdown = 'form_id';
	$file_uploads->record_limit = 50;
	$file_uploads->checkout = $dashboard->checkout;
	$file_uploads->show_delete  = true;
	
	
	$output .= '<div class="hidden">';
	$output .= $dashboard->dashboard_menu('File Uploads');
	$output .= '</div>';
	
	
	
	$output .= '<div class="admin_url" style="display:none;">'.admin_url().'</div>';
	$nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
	$output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';
	
	$output .= '<div class="nf_context_menu nf_context_menu_2 aa_menu aa_bg_main">
				
				<ul class="aa_menu">
					<li class="cm-action-item mark-read" data-action="mark-read"><a href="#" class="cm-item-text"><span class="fas fa-eye"></span>Mark as read</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item mark-unread" data-action="mark-unread"><a href="#" class="cm-item-text"><span class="fas fa-eye-slash"></span>Mark as unread</span><span class="kbsc"></span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item" data-action="add-star"><a href="#" class="cm-item-text"><span class="fas fa-star"></span> Add Star</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item" data-action="remove-star"><a href="#" class="cm-item-text"><span class="far fa-star"></span> remove Star</span><span class="kbsc"></span></a></li>
					
					<li class="sec_divider restore_record" tyle="display:none;"></li>
					<li class="cm-action-item restore_record" data-action="restore" style="display:none;"><a href="#" class="cm-item-text"><span class="fas fa-trash-restore"></span> Restore</span><span class="kbsc"></span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item" data-action="delete"><a href="#" class="cm-item-text"><span class="fas fa-trash"></span> Delete</span><span class="kbsc">Delete</span></a></li>
				<ul>
			</div>';
	
	
	$output .= '<div id="dashboard_panel" class="dashboard_panel">';
	$output .= $dashboard->new_menu();
	
	
	$output .= '<div id="nex_forms_entries" class="nex_forms_entries file_uploads">';
		
		$output .= '<div class="entries_wrapper">';
			
			$output .= '<div class="left-col aa_bg_main">';
					
				$output .= $dashboard->uploads_menu();
				
			$output .= '</div>';
			
			$output .= '<div class="right-col">';
				$output .= '<div class="right-col-top">';
					$output .= $file_uploads->print_record_table();
					
					
					
				$output .= '</div>';
				/*$output .= '<div class="right-mid">';
					
					
					
					$output .= '<div class="entry_tools aa_bg_main">';
						
						$output .= '<button type="submit" class="save_form_entry save_button button button-primary" style="display:none;">'.__('Save','nex-forms').'</button>';
						$output .= '<button class="cancel_save_form_entry save_button button button-primary" style="display:none;"><i class="fa fa-close"></i></button>';
						
						
						$output .= '<div class="entry_views">';
						
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch  view_form_data active" data-action="view-data" disabled="disabled"><span class="fas fa-database"></span> '.__('Entry Data','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch" data-action="view-admin-email" disabled="disabled"><span class="fas fa-envelope"></span> '.__('View Admin Email','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch" data-action="view-user-email" disabled="disabled"><span class="far fa-envelope"></span> '.__('View User Email','nex-forms').'</button>';
						$output .= '</div>';
					
						$output .= '<div class="entry_actions">';	
							$output .= '<button class="print_to_pdf aa_bg_main_btn no_batch nf_button" disabled="disabled"><span class="fas fa-file-pdf"></span> '.__('Export to PDF','nex-forms').'</button>';
							//$output .= '<button class="button no_batch do_action" data-action="print-form-entry" disabled="disabled"><span class="fas fa-print"></span> '.__('Print','nex-forms').'</button>';
							$output .= '<button id="" class="edit_form_entry aa_bg_main_btn no_batch nf_button" disabled="disabled"><span class="fas fa-pen-square"></span> '.__('Edit','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action" data-action="delete" disabled="disabled"><span class="fas fa-trash"></span> '.__('Delete','nex-forms').'</button>';
						$output .= '</div>';
						
					$output .= '</div>';
					
				$output .= '</div>';
				$output .= '<div class="right-bottom">';
					$output .= $file_uploads->print_form_entry();
				$output .= '</div>';
				*/
			$output .= '</div>';
			
		$output .= '</div>';
		
	
		//
		
		
		//
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
	
	NEXForms_clean_echo( $output);
	
	$dashboard->remove_unwanted_styles();
	
	update_option('nf_activated',$dashboard->checkout);
}

function NEXForms_global_setup_page(){
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	$nf_function = new NEXForms_functions();
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	
	$nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
	$output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';
	
	$output .= '<div class="nex_forms_admin_page_wrapper">';


	$output .= '<div class="hidden">';
	$output .= $dashboard->dashboard_menu('Settings');
	$output .= '</div>';
		 
		  $output .= '<div id="dashboard_panel" class="dashboard_panel global_settings_page">';
			$output .= $dashboard->new_menu();
			  $output .= '<div class="row row_zero_margin ">';
			  	
				//EMAIL SETUP
				$output .= '<div class="col-sm-4">';
					$output .= $dashboard->license_setup($dashboard->checkout, $dashboard->client_info, $dashboard->license_info);
					$output .= $dashboard->email_setup();
				$output .= '</div>';
			  	
				//WP ADMIN OPTIONS
				$output .= '<div class="col-sm-4">';
					$output .= $dashboard->preferences();
					$output .= $dashboard->wp_admin_options();
				$output .= '</div>';
				
			  	//PREFERENCES
				$output .= '<div class="col-sm-4">';
					$output .= $dashboard->email_subscriptions_setup();
					$output .= $dashboard->troubleshooting_options();
				$output .= '</div>';
				
			$output .= '</div>';
			  
		  $output .= '</div>';
		  	 $output .= '</div>';
	 $output .= '</div>'; //nex_forms_admin_page_wrapper
 
	 NEXForms_clean_echo( $output);
	 $dashboard->remove_unwanted_styles();
	
	
	update_option('nf_activated',$dashboard->checkout);
	
	
}

function NEXForms_get_add_ons(){
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	NEXForms_set_add_ons();
	
	$nf_function = new NEXForms_functions();
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	$nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
	$output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';
	$output .= '<div class="nex_forms_admin_page_wrapper">';
	$output .= '<div class="hidden">';
		$output .= $dashboard->dashboard_menu('Add-ons');
	$output .= '</div>';		 
			 $output .= '<div id="dashboard_panel" class="dashboard_panel global_settings_page">';
				$output .= $dashboard->new_menu('add-ons');
	$get_current_user_plan = 'Free';
	if(get_option('nf_activated'))
			{
			$get_current_user_plan = 'Envato';	
			}
		else{
	if(function_exists('nf_fs'))
		{
		
		if ( nf_fs()->is_plan('elite', true) ) {
    		$get_current_user_plan = 'Elite';
			}
		if ( nf_fs()->is_plan('pro', true) ) {
    		$get_current_user_plan = 'Pro';
			}
		if ( nf_fs()->is_plan('plus', true) ) {
    		$get_current_user_plan = 'Plus';
			}
		if ( nf_fs()->is_plan('basic', true) ) {
    		$get_current_user_plan = 'Basic';
			}
		
		}
		}
	//MY FORMS
	$add_ons = new NEXForms_dashboard();
	$add_ons->table = 'wap_nex_forms_add_ons';
	$add_ons->table_header = 'Add-ons';
	$add_ons->table_header_icon = '';
	$add_ons->extra_buttons = array(
								'Active'		=>array('class'=>'filter_add_on_active filter_add_on ', 		'id'=>'filter_add_on_active', 		'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="far fa-circle-check"></span> '.__('&nbsp;Active','nex-forms').''),
								'Inactive'		=>array('class'=>'filter_add_on_inactive filter_add_on ', 		'id'=>'filter_add_on_inactive', 	'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="far fa-circle-stop"></span> '.__('&nbsp;Inactive','nex-forms').''),
								'Not Installed'	=>array('class'=>'filter_add_on_not_installed filter_add_on ', 	'id'=>'filter_add_on_not_installed', 'type'=>'button','link'=>'', 'rank'=>'2', 'icon'=>'<span class="far fa-circle-xmark"></span> '.__('&nbsp;Not Installed','nex-forms').'')
								//'read'			=>array('class'=>'filter_read filter_button', 			'id'=>'filter_read', 		'type'=>'button','link'=>'', 'icon'=>'<span class="fas fa-eye"></span> '.__('&nbsp;Read','nex-forms').''),
								);
	$add_ons->table_headings = array(
	'title',
	array('heading'=>__('Description','nex-forms'), 'user_func'=>'NEXForms_get_add_on_description','user_func_args_1'=>'Id','sort_by'=>'description'),
	
	//array('heading'=>__('Plan Availability','nex-forms'), 'user_func'=>'NEXForms_get_add_on_plans','user_func_args_1'=>'Id'),
	//array('heading'=>__('Avialable Plans','nex-forms'), 'user_func'=>'NEXForms_get_add_on_plans','user_func_args_1'=>'Id'),
	array('heading'=>__('Version','nex-forms'), 'user_func'=>'NEXForms_get_add_on_version','user_func_args_1'=>'Id'),
	array('heading'=>__('Status','nex-forms'), 'user_func'=>'NEXForms_get_add_on_status','user_func_args_1'=>'Id'),
	array('heading'=>__('Action','nex-forms'), 'user_func'=>'NEXForms_install_add_on','user_func_args_1'=>'Id'),
	);
	$add_ons->show_headings=true;
	$add_ons->extra_classes = 'my-forms chart-selection';
	$add_ons->search_params = array('title', 'description','plans');
	$add_ons->checkout = $dashboard->checkout; 
	
	//$saved_forms->extra_buttons = array('new_form'=>array('class'=>'create_new_form', 'id'=>isset($_POST['form_Id']) ? sanitize_text_field($_POST['form_Id']) : '', 'type'=>'button','link'=>'', 'icon'=>'<span class="fas fa-file-medical"></span> '.__('&nbsp;&nbsp;Add a New Form','nex-forms').''));
	$add_ons->color_adapt = true;
	$add_ons->show_delete = false;
	$add_ons->record_limit = 10;
	
	$output .= '<div class="col-sm-3">';
		
		
		$output .= '<div class="stats_container">';
		
		 $output .= '<div class="row">';
				$output .= '<div class="stats_summary_container" style="margin-top:-5px;">';
				$output .= '<div class="geo_heading">'.__($get_current_user_plan.' Plan - Add-ons','nex-forms').'</div>';	
					$output .= '
							<div class="stats_summary_container3">
					  			<div class="row stats aa_bg_sec header_stats2">';
								$output .= NEXForms_get_add_on_stats();
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
									
			$output .= '</div>'; 
		$output .= '</div>';
		
		
	$output .= '</div>';
	
	$output .= '<div class="col-sm-9">';
		$output .= $add_ons->print_record_table();
	$output .= '</div>';
	
	
	$output .= '</div>';
		 $output .= '</div>';
	 $output .= '</div>';
	 NEXForms_clean_echo( $output);
	 $dashboard->remove_unwanted_styles();
}

add_action('wp_ajax_nexforms_get_add_on_stats', 'NEXForms_get_add_on_stats');

function NEXForms_get_add_on_stats(){
	global $wpdb;
	$count_add_ons = $wpdb->get_var('SELECT COUNT(*) FROM `'.$wpdb->prefix.'wap_nex_forms_add_ons` WHERE plans LIKE \'%'.$get_current_user_plan.'%\';'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$get_installed_add_ons = $wpdb->get_results('SELECT * FROM `'.$wpdb->prefix.'wap_nex_forms_add_ons`');
		
		$all_plugins = get_plugins();
		
		$count_installed_add_ons = 0;
		$count_active_add_ons = 0;
		$count_inactive_add_ons = 0;
		foreach($get_installed_add_ons as $add_on)
			{
			if (isset($all_plugins[$add_on->add_on_url.'/main.php']) || isset($all_plugins[$add_on->add_on_url.'/'.$add_on->add_on_url.'.php']))
				{
				$count_installed_add_ons++;
				if (is_plugin_active($add_on->add_on_url.'/main.php') || is_plugin_active($add_on->add_on_url.'/'.$add_on->add_on_url.'.php'))
					{
					$count_active_add_ons++;
					}
				else
					{	
					$count_inactive_add_ons++;
					}
				}
			}							
			$output .= '<div class="col-xs-3"><span class="big_txt">'.$count_add_ons.'</span>
							  <label style="cursor:default;color:#6ca6e5">Avalialable Add-ons</label>
							</div>
							<div class="col-xs-3"><span class="big_txt">'.$count_installed_add_ons.'</span>
							  <label style="cursor:default;color:#1875d0">Installed Add-ons</label>
							</div>
							<div class="col-xs-3"><span class="big_txt">'.$count_active_add_ons.'</span>
							  <label style="cursor:default;color:#1875d0">Active Add-ons</label>
							</div>
							<div class="col-xs-3"><span class="big_txt">'.$count_inactive_add_ons.'</span>
							  <label style="cursor:default;color:#1875d0">Inactive Add-ons</label>
							</div>
							';
							
		$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';
	
		if($do_ajax)
			{
			NEXForms_clean_echo($output);
			wp_die();
			}
		else
			return $output;
}
function NEXForms_add_ons_page(){
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	$nf_function = new NEXForms_functions();
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	
	
	
	
	
	$get_info = $dashboard->client_info;
				
				$get_license = $dashboard->license_info;
				
				$set_year 	=  	2021; //substr($get_info['date_puchased'],0,4);
				$set_month 	= 	8; //substr($get_info['date_puchased'],5,2);
				$set_day 	= 	20; //substr($get_info['date_puchased'],8,2);
				
				$supported_until = (isset($get_license['supported_until']) ? $get_license['supported_until'] : '');
				
				$set_support_year 	=  	substr($supported_until,0,4);
				$set_support_month 	= 	substr($supported_until,5,2);
				$set_support_day 	= 	substr($supported_until,8,2);
				
				$get_support_date = (isset($get_info['expiration_date']) ? $get_info['expiration_date'] : '');
				
				$date1 = $set_support_year.'-'.$set_support_month.'-'.$set_support_day;
				$date2 = date('yy-m-d');
				
				$diff = strtotime($date1) - strtotime($date2);
				
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

				if($set_year==2020 || $set_year==2021 || $set_year==2022)
					{
					$download=true;		
					}
				
					
					
				if($download && $diff>0)
					$output .= '<div class="set_free_add_ons hidden">true</div>';
				else
					{
					}//
		
		
		$output .= '<div class="nex_forms_admin_page_wrapper">';
$output .= '<div class="hidden">';
	$output .= $dashboard->dashboard_menu('Add-ons');
$output .= '</div>';		 
		 $output .= '<div id="dashboard_panel" class="dashboard_panel global_settings_page">';
			$output .= $dashboard->new_menu('add-ons');
				
				
				
				//PAYPAL PRO
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/paypal-pro/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-paypal-pro.png',  dirname(dirname(__FILE__))).'"></a>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>PayPal Pro</h3>';
							
							$output .= 'Enable online payments through PayPal. Incudes Itemized PayPal checkout and email sending options based on payment status.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_get_paypal_payment'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//PDF CREATOR
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/pdf-creator/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-pdf-creator.png',  dirname(dirname(__FILE__))).'"></a>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>PDF Creator</h3>';
							$output .= 'Enables custom PDF creation from submmited form data. Also include options for these PDF\'s to be attached to admin and user emails.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_pdf'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//MULTI-PAGE FORMS
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-multi-page-forms.png',  dirname(dirname(__FILE__))).'"></a>';	 // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Multi-Page Forms</h3>';
							$output .= 'Enables multi-page forms allowing submitted form data to be sent from one form to the next.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nex_forms_not_found_notice_mpf'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				//FORM THEMES
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/form-themes/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-form-themes.png',  dirname(dirname(__FILE__))).'"></a>';	 // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Form Themes/Color Schemes</h3>';
							$output .= 'Instantly fit your form design to your site\'s look and feel. Switch forms to Bootstrap, Material Design, Neumorphism, JQuery UI or Classic Themes. Includes 44 Preset Color Schemes.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ft'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				//ZAPIER
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/zapier-integration/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-zapier.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Zapier Integration</h3>';
							$output .= 'Enables the integration of NEX-Forms to over 4000 apps.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('NEXForms_not_found_notice_zapier'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				//DIGITAL SIGNATURES
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/digital-signatures/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-digital-signatures.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Digital / E-Signatures</h3>';
							$output .= 'Allows you to add digital signature fields to your forms. Use these signatures in email and PDF\'s.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ds'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';	
				$output .= '</div>';
				
				
				//SUPER SELECT
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/super-select-form-field/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-super-select.png', dirname(dirname(__FILE__))).'"></a>';// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Super Selection Form Field</h3>';
							$output .= 'Use 1500+ Icons to create your own custom Radio Buttons, Checkboxes, Dropdown selects and Spinner selects. Abolutely Full Cutomisation...use any on/off colors and any on/off icons for each option.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ss'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a  href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				//STRIPE
				/*$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/stripe/" target="_blank"><img src="https://basixonline.net/add-ons/covers/nex-forms-add-on-stripe.png"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Stripe</h3>';
							$output .= 'Enable online payments through Stripe<br /><br /><br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_stripe'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';*/
			  	
				
				
					
				
				
				//FORM TO POST
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/form-to-post-or-page/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-form-to-post-or-page.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Form to POST / PAGE</h3>';
							$output .= 'Automatically create posts or pages from NEX-Forms form submissions. Includes setting featured image and the use of data tags to populate Page/Post content.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_ftp_setup'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				
				
				
				
				
				//CONDITIONAL CONTENT BLOCKS
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/conditional-content-blocks/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-conditional-content-blocks.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Conditional Content Blocks</h3>';
							$output .= 'Create dynamic content in emails and PDF\'s from submitted data. Meaning you can hide/show specific content in the emails or PDF\'s based on a users input or selection.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ccb'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				//SHORTCODE PROCESSOR
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/shortcode-processor/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-shortcode-processor.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Shorcode Processor</h3>';
							$output .= 'Run your own custom shorcode or 3rd party plugin/theme shorcode anywhere in your forms.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_sp'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				
				//MAILCHIMP
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/mailchimp/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-mailchimp.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>MailChimp</h3>';
							$output .= 'Automatically update your MailChimp lists with new subscribers from NEX-Forms. ';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_mc_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				//MAILSTER
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/mailchimp/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-mailster.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Mailster</h3>';
							$output .= 'Automatically update your Mailster lists with new subscribers from NEX-Forms. ';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_ms_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//MAILPOET
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/mailchimp/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-mailpoet.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>MailPoet</h3>';
							$output .= 'Automatically update your MailPoet lists with new subscribers from NEX-Forms. ';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_mp_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				//GETRESPONSE
				$output .= '<div class="col-sm-12">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/getresponse/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-getresponse.png',  dirname(dirname(__FILE__))).'"></a>';	// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>GetRepsonse</h3>';
							$output .= 'Automatically update your GetResponse lists with new subscribers from NEX-Forms.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_gr_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" class="buy_add_on" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				
				
				
				
			$output .= '</div>';
		 $output .= '</div>';
	 $output .= '</div>';
	 NEXForms_clean_echo( $output);
	 $dashboard->remove_unwanted_styles();
	
}

function NEXForms_dashboard(){
	
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	$nf_function = new NEXForms_functions();
	
	
	$count_entries = $wpdb->get_results('SELECT nex_forms_Id, COUNT(nex_forms_Id) as counted FROM `'.$wpdb->prefix.'wap_nex_forms_entries` WHERE trashed IS NULL GROUP BY nex_forms_Id;'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			foreach($count_entries as $entry)
				{
				$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms', array('entry_count'=>$entry->counted), array('Id' => $entry->nex_forms_Id) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
	
	
	$config = new NEXForms5_Config();
	
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	$dashboard->color_adapt = true;
	
	//MY FORMS
	$saved_forms = new NEXForms_dashboard();
	$saved_forms->table = 'wap_nex_forms';
	$saved_forms->table_header = 'Forms';
	$saved_forms->table_header_icon = 'insert_drive_file';
	$saved_forms->table_headings = array('Id', array('heading'=>__('title','nex-forms'), 'user_func'=>'link_form_title_2', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id','sort_by'=>'title'),array('heading'=>__('Shortcode','nex-forms'), 'user_func'=>'get_form_shortcode', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id', 'sort_by'=>'Id'), array('heading'=>__('Total Entries','nex-forms'), 'user_func'=>'get_total_entries_3', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id','user_func_args_2'=>'entry_count', 'sort_by'=>'entry_count'),array('heading'=>'', 'user_func'=>'link_form_title', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'),array('heading'=>'', 'user_func'=>'duplicate_record', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'),array('heading'=>'', 'user_func'=>'print_export_form_link', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'));
	$saved_forms->show_headings=true;
	$saved_forms->extra_classes = 'my-forms chart-selection';
	$saved_forms->additional_params = array(array('column'=>'is_form','operator'=>'!=','value'=>'preview'));
	$saved_forms->search_params = array('Id','title');
	$saved_forms->checkout = $dashboard->checkout;
	//$saved_forms->extra_buttons = array('new_form'=>array('class'=>'create_new_form', 'id'=>isset($_POST['form_Id']) ? sanitize_text_field($_POST['form_Id']) : '', 'type'=>'button','link'=>'', 'icon'=>'<span class="fas fa-file-medical"></span> '.__('&nbsp;&nbsp;Add a New Form','nex-forms').''));
	$saved_forms->color_adapt = true;
	$saved_forms->show_delete = true;
	
	//LATEST ENTRIES
	$latest_entries = new NEXForms_dashboard();
	$latest_entries->table = 'wap_nex_forms_entries';
	$latest_entries->table_header = 'Last 10 Form Submissions';
	$latest_entries->sortable_columns = false;
	$latest_entries->table_header_icon = 'assignment';
	$latest_entries->table_headings = array(array('heading'=> __('Form','nex-forms'), 'user_func'=>'NEXForms_get_title','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'),/*'page',*/array('heading'=>__('Submitted','nex-forms'), 'user_func'=>'NEXForms_time_elapsed_string','user_func_args_1'=>'date_time', 'user_func_args_2'=>'wap_nex_forms'),array('heading'=>__('Data Summary','nex-forms'), 'user_func'=>'NEXForms_get_entry_data_preview','user_func_args_1'=>'Id'));
	$latest_entries->show_headings=true;
	$latest_entries->search_params = array('Id','form_data');
	$latest_entries->checkout = $dashboard->checkout;
	$latest_entries->show_delete = true;
	$latest_entries->show_paging = false;
	$latest_entries->show_search = false;
	$latest_entries->color_adapt = true;
	
	$latest_entries->show_more_link = array('link'=> get_admin_url().'admin.php?page=nex-forms-page-submissions','text'=>'Show all form entries');
	
	
	$output .= '<div class="nex_forms_admin_page_wrapper">';
	$output .= $nf_function->new_form_setup($dashboard->checkout);
    $output .= '<div class="hidden">';
	$output .= $dashboard->dashboard_menu('Dashboard');
	$output .= '</div>';
	
					
	 
	 
	
	
	
		$get_info = $dashboard->client_info;
				
		$get_license = $dashboard->license_info;
	 
	 
	 
		 
		 
		if ( !nf_fs()->can_use_premium_code() )
		 	{
			$supported_until = $license_info['supported_until'];
			$supported_date = new DateTime($supported_until);
			$now = new DateTime();
					
			if(get_option('NFISENVA'))
				{
				if ($supported_date < $now)	
					{
					 $output .= '<div id="env_support" style="display:none;">0</div>';
					}
				}
			}
					
		 
		 
		 
		 
		 $nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
		 $output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';
			update_option('nf_activated',$dashboard->checkout);
			
				
			
								
		  
		  
		  
		  $output .= '<div id="dashboard_panel" class="dashboard_panel">';
		  	  
			  

	
			  
			
			$output .= $dashboard->new_menu('dashboard');
			  	
					
					//$output .= '</div>';
					if ( !$dashboard->checkout )
						{
						$output .= '<div class="col-sm-3">';
						$output .= '<div class="dashboard-box global_settings">';
							$output .= '<div class="dashboard-box-header aa_bg_main">';
								$output .= '<div class="table_title"><i class="material-icons header-icon">verified_user</i>'.__('NEX-Forms License','nex-forms').'</div>';
								$output .= '<p class="box-info"><strong>Status:</strong> '.(($checked=='true') ? '<span class="label label-success">'.__('Activated','nex-forms').'</span>' : '<span class="label label-danger">'.__('Not Activated','nex-forms').'</span>').'</p>';
							$output .= '</div>';
							
							$output .= '<div  class="dashboard-box-content activation_box">';
								$output .= nf_fs()->_connect_page_render();	
								
								$output .= '<div class="alert alert-info">Currently, your NEX-Forms installation is not activated, which means some key features are disabled. To unlock these features you need to <a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=top_nav" target="_blank"><strong>upgrade to PREMIUM</strong></a></div>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '</div>';
						}
					 
					
					$output .= '<div class="col-sm-2">';
					
					//$output .= '<button id="upload_form" class="form-control  btn blue waves-effect waves-light import_form">'.__('Import Form','nex-forms').'</button>';
						$output .= '<div class="dashboard-box database_table create_new_form wap_nex_forms new-forms">';
								$output .= '<div class="">
								
								<span class="icon fas fa-solid fa-file-circle-plus"></span><br />
								
								'.__('Add a New Form','nex-forms').'
								
								</div>
						</div>';
						
						
						$output .= '<div class="dashboard-box database_table create_new_form create-template wap_nex_forms new-forms">';
								$output .= '<div class="">
								
								<span class="icon fas fa-file-invoice"></span><br />
								'.((!$dashboard->checkout) ? '' : '').''.__('Load Form Template','nex-forms').'
								
								
								
						</div></div>';
						
						$output .= '<div class="dashboard-box database_table create_new_form do-tut wap_nex_forms new-forms">';
								$output .= '<div class="">
								
								<span class="icon fas fa-graduation-cap"></span><br />
								'.__('Tutorials','nex-forms').'
								
								
								
						</div></div>';
						
						
						$output .= '<div id="'.(($dashboard->checkout) ? 'upload_form' : '').'" class="dashboard-box database_table '.(($dashboard->checkout) ? 'import_form' : 'import_form2').'  wap_nex_forms new-forms">';
								$output .= '<div class="">
								
								<span class="icon fas fa-file-import"></span><br />
								'.((!$dashboard->checkout) ? '' : '').''.__('Import Form','nex-forms').'
								
								
								
						</div>';
						
						
						/*$output .= '<div class="dashboard-box-header aa_bg_main"><div class="table_title font_color_1 ">New Form</div></div>
						
						
						<div class="dashboard-box-new">
						';
							
							
							
							//
							
							
							$output .= '<form class="new_nex_form" name="new_nex_form" id="new_nex_form" method="post" action="'.admin_url('admin-ajax.php').'">';
						
								//$output .= '<h5><strong>'.__('Create a new Blank Form','nex-forms').'</strong></h5>';
								
								$nonce_url = wp_create_nonce( 'nf_admin_new_form_actions' );
		 						$output .= '<input name="nex_forms_wpnonce" type="hidden" value="'.$nonce_url.'">';
								
								$output .= '<input name="title" id="form_title" placeholder="'.__('Enter new Form Title','nex-forms').'" class="form-control" type="text">';		
						
								$output .= '<button type="submit" class="form-control submit_new_form btn blue waves-effect waves-light">'.__('Create','nex-forms').'</button>';
							
							$output .= '</form>';
							
							$output .= '</div>';*/
						$output .= '</div>';
					$output .= '</div>';
					
					$output .= '<div class="col-sm-'.(($dashboard->checkout) ? '10' : '7').'">';
						$output .= $saved_forms->print_record_table();
					$output .= '</div>';
					
					
					
					
					
					
			  $output .= '</div>';
			  
			  $output .= '<div class="row row_zero_margin ">';
					
					$output .= '<div  class="col-sm-5">';
						$output .= $dashboard->form_analytics($print_chart='summary');
					$output .= '</div>';
					
					$output .= '<div class="col-sm-7">';
						$output .= $latest_entries->print_record_table();
					$output .= '</div>';
					
					//$output .= '<div  class="col-sm-6">';
					//	$output .= $latest_entries->print_form_entry();
					//$output .= '</div>';
			  $output .= '</div>';
			  
		  $output .= '</div>';
		$output .= '</div>';  	
	 $output .= '</div>'; //nex_forms_admin_page_wrapper
 
	 echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	 $dashboard->remove_unwanted_styles();
	 
}




function NEXForms_reporting_page_new(){
	
	global $wpdb;
	$theme = wp_get_theme();
	$output = '';
	
	$nf_function = new NEXForms_functions();

	$config = new NEXForms5_Config();
	
	$dashboard = new NEXForms_dashboard();
	$dashboard->dashboard_checkout();
	$dashboard->color_adapt = true;
	
	//MY FORMS
	$saved_forms = new NEXForms_dashboard();
	$saved_forms->table = 'wap_nex_forms_reports';
	$saved_forms->table_header = 'Saved Reports';
	$saved_forms->table_header_icon = 'insert_drive_file';
	$saved_forms->table_headings = array('Id',  array('heading'=>__('title','nex-forms'), 'user_func'=>'link_report_title', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id','sort_by'=>'title'), array('heading'=> __('Reporting Form','nex-forms'), 'user_func'=>'NEXForms_get_title3','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms','sort_by'=>'nex_forms_Id'), array('heading'=>__('Total Records','nex-forms'), 'user_func'=>'get_total_report_records', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'db_table'), array('heading'=>__('Last Update','nex-forms'), 'user_func'=>'report_last_update', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'date_time','sort_by'=>'date_time'), array('heading'=>'Export CSV', 'user_func'=>'quick_report_csv', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'), array('heading'=>'Export PDF', 'user_func'=>'quick_report_pdf', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'), array('heading'=>'View/Edit', 'user_func'=>'link_report_title2', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'));
	$saved_forms->show_headings=true;
	$saved_forms->extra_classes = 'my-forms chart-selection';
	$saved_forms->search_params = array('Id','title');
	$saved_forms->checkout = $dashboard->checkout;
	$saved_forms->color_adapt = true;
	$saved_forms->show_delete = true;
	
	
	$output .= '<div class="nex_forms_admin_page_wrapper">';
	$output .= NEXForms_new_report_setup();
    $output .= '<div class="hidden">';
	$output .= $dashboard->dashboard_menu('Dashboard');
	$output .= '</div>';
	
	
		update_option('nf_activated',$dashboard->checkout);
	 
		 
		 $nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
		 $output .= '<div id="nex_forms_wpnonce" style="display:none;">'.$nonce_url.'</div>';
		 
		  $output .= '<div id="dashboard_panel" class="dashboard_panel nf_reporting">';
			$output .= $dashboard->new_menu('dashboard');
			  	$output .= '<div class="col-sm-2">';
					
					$output .= '<div class="dashboard-box database_table create_new_form new_report wap_nex_forms new-forms">';
						$output .= '<div class=""><span class="icon fas fa-database"></span><br />'.__('Create a New Report','nex-forms').'</div></div>';	
					$output .= '</div>';
					
					$output .= '<div class="col-sm-10">';
						$output .= $saved_forms->print_record_table();
					$output .= '</div>';
					
			  $output .= '</div>';
		  $output .= '</div>';
		$output .= '</div>';  	
	 $output .= '</div>'; //nex_forms_admin_page_wrapper
 
	 NEXForms_clean_echo( $output);
	 $dashboard->remove_unwanted_styles();
	 
}



function NEXForms_new_report_setup(){

		$output = '';
		
		
		global $wpdb;
		$dashboard = new NEXForms_dashboard();
		$dashboard->dashboard_checkout();
		$database_actions = new NEXForms_Database_Actions();
		
		
		
		
		$nonce_url = wp_create_nonce( 'nf_admin_new_form_actions' );
		$output .= '<div id="new_form_setup" class="modal animated fadeInDown">';
			//HEADER 
			//$theme = wp_get_theme();
			
			$output .= '<div class="modal-header aa_bg_main">';
				$output .= '<div class="modal-close back-to-dash reporting"><span class="fas fa-arrow-left"></span></div><h4><div class="report-bc">'.__('New Report','nex-forms').'</div> - <div class="sub-heading"></div><div class="report-name"></div></h4>';
				$output .= '<i class="modal-action modal-close"><i class="fa fa-close"></i></i>';
			$output .= '</div>';
			//CONTENT
			$output .= '<div class="modal-content">';

				$output .= '<div class="new-report-container" >';
					$output .= '<div class="dash-left-col">';
					$output .= '<div class="new-form-sidebar2 aa_bg_sec aa_menu" >';
						$output .= '<ul>';
								
								$output .= '<li class="db_tab menu-item-has-children active"><a class="" data-panel="panel-1" data-sub-heading="'.__('1. Report Setup','nex-forms').'"><span class="top-icon fa-solid fa-wrench"></span> <span class="menu-text">'.__('Report Setup','nex-forms').'</span></a></li>';
								$output .= '<li class="db_tab menu-item-has-children disabled"><a class="" data-panel="panel-2" data-sub-heading="'.__('2. Reporting Field Selection','nex-forms').'"><span class="top-icon fa-solid fa-file-circle-plus"></span> <span class="menu-text">'.__('Reporting Fields','nex-forms').'</span></a></li>';
								$output .= '<li class="db_tab menu-item-has-children disabled"><a class="" data-panel="panel-3" data-sub-heading=""><span class="top-icon fa-solid fa-diagram-next"></span> <span class="menu-text">'.__('Generate Report','nex-forms').'</span></a></li>';
	
						$output .= '<ul>';
					$output .= '</div>';
					$output .= '</div>';
					
					//BLANK
				$output .= '<div class="rep-center-col">';
					$output .= '<div class="new-form-panel ajax_loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
					$output .= '<div class="new-form-panel ajax_error_response">';
					$output .= '<div class="alert alert-danger">'.__('Sorry, something went wrong while reading the import file. Please try MANUAL IMPORT instead.','nex-forms').'</div>';
					$output .= '</div>';
					$output .= '<input name="title2" id="form_title" placeholder="'.__('Enter new Form Title','nex-forms').'" class="hidden" type="text">';	
					$output .= '<form class="new_nex_form_report" name="new_nex_form_report" id="new_nex_form_report" method="post" action="'.admin_url('admin-ajax.php').'">';
						$output .= '<input name="nex_forms_wpnonce" type="hidden" value="'.$nonce_url.'">';
						$output .= '<div class="report_steps">';
							$output .= $dashboard->edit_report();
						$output .= '</div>';
					$output .= '</form>';
				$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';				  
				
			return $output;
		
	}

if(!class_exists('NEXForms_dashboard'))
	{
	class NEXForms_dashboard{
		public 
		$table = 'wap_nex_forms',
		$table_header = '',
		$extra_classes = '',
		$table_header_icon = '',
		$additional_params = array(),
		$show_search = true,
		$search_params = array(),
		$build_table_dropdown = false,
		$table_headings = array(),
		$field_selection = array(),
		$extra_buttons = array(),
		$show_headings = true,
		$show_delete = true,
		$show_paging = true,
		$table_resize =false,
		$checkout = false,
		$client_info = 'no info',
		$is_report=false,
		$action_button,
		$color_adapt=false,
		$record_limit=10,
		$sortable_columns = true,
		$action='',
		$show_more_link = '';
		
		public function __construct($table='', $table_header='', $extra_classes='', $table_header_icon='',$additional_params='', $search_params='', $table_headings='', $show_headings='', $field_selection ='', $extra_buttons ='', $checkout=false, $sortable_columns = true, $show_search=true, $show_paging=true, $show_delete=false, $is_report=false, $color_adapt=false, $table_resize=false , $record_limit=10, $action=''){
			
			global $wpdb; 
		
			
			
			add_action('wp_ajax_get_table_records', array($this,'get_table_records'));
			add_action('wp_ajax_do_form_entry_save', array($this,'do_form_entry_save'));
			add_action('wp_ajax_nf_report_get_additional_params', array($this,'report_get_additional_params'));
			
			add_action('wp_ajax_submission_report2', array($this,'submission_report2'));
			
			add_action('wp_ajax_nf_print_chart', array($this,'print_chart'));
			
			add_action('wp_ajax_nf_delete_form_entry', array($this,'delete_form_entry'));
			
			add_action('wp_ajax_nf_entries_restore', array($this,'restore_records'));
			
			add_action('wp_ajax_nf_entries_set_starred', array($this,'set_starred'));
			add_action('wp_ajax_nf_entries_set_read', array($this,'set_read'));
			
			add_action('wp_ajax_nf_reset_forms_menu', array($this,'entries_menu'));
			
			
			add_action('wp_ajax_nf_print_to_pdf', array($this,'print_to_pdf'));
			add_action('wp_ajax_nf_delete_pdf', array($this,'delete_pdf'));
			
			
			
			
			add_action('wp_ajax_nf_create_new_report', array($this,'create_report'));
			add_action('wp_ajax_nf_edit_report', array($this,'edit_report'));

			
			//add_action('wp_ajax_nopriv_nf_print_to_pdf', array($this,'print_to_pdf'));
			
			add_action('wp_ajax_nf_print_report_to_pdf', array($this,'print_report_to_pdf'));
			//add_action('wp_ajax_nopriv_nf_print_report_to_pdf', array($this,'print_report_to_pdf'));
			
			$this->table 				= $table;
			$this->table_resize 		= $table_resize;
			$this->table_header 		= $table_header;
			$this->table_header_icon	= $table_header_icon;
			$this->additional_params 	= $additional_params;
			$this->search_params 		= $search_params;
			$this->field_selection 		= $field_selection;
			$this->table_headings		= $table_headings;
			$this->show_headings		= $show_headings;
			$this->show_delete			= $show_delete;
			$this->show_paging			= $show_paging;
			$this->extra_buttons		= $extra_buttons;
			$this->extra_classes		= $extra_classes;
			$this->is_report			= $is_report;
			$this->color_adapt			= $color_adapt;
			$this->record_limit			= $record_limit;
			$this->action				= $action;
			$this->sortable_columns		= $sortable_columns;
			}
		
		public function edit_report(){
	
		global $wpdb;
		$dashboard = new NEXForms_dashboard();
		$dashboard->dashboard_checkout();
		$database_actions = new NEXForms_Database_Actions();
		$nf_function = new NEXForms_functions();
		$nf_functions = new NEXForms_functions();
		$report_id = (isset($_POST['report_update_id'])) ? sanitize_title($_POST['report_update_id']) : 0;
		$output = '';
		$report_title = '';
		$report_form = '';
		$get_report = false;
		if($report_id)
			{
			$get_report = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id=%d',$report_id)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery	
			
			
			$report_title 	= $get_report->report_title;
			$report_form 	= $get_report->nex_forms_Id;
			$output .= '<div id="report-edit-Id" style="display:none;">'.$report_id.'</div>';
			
			$output .= '<div id="report-status" style="display:none;">'.$get_report->status.'</div>';
			}
		
		$output .= '<div class="new-report-panel new-report panel-1 active">';
									$output .= '<br /><br /><div class="row">';
						$output .= '<div class="col-sm-1"></div>';
							$output .= '<div class="col-sm-10">';
								$output .= '<div class="dashboard-box database_table wap_nex_forms">
								
								<div class="dashboard-box-header aa_bg_main"><div class="table_title font_color_1 ">'.__('Create a new Submission Report','nex-forms').'</div></div>
								<div class="dashboard-box-content ">
								';
								
									//$output .= '<h5><strong>'.__('Create a new Blank Form','nex-forms').'</strong></h5>';
									
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-2">';
											$output .= '<div class="report-setting-label">'.__('Report Title','nex-forms').'</div>';
										$output .= '</div>';
										$output .= '<div class="col-sm-10">';
											$output .= '<input name="report_title" id="report_title" value="'.$report_title.'" maxlength="35" placeholder="'.__('Enter Report Title','nex-forms').'" class="form-control" type="text">';		
										$output .= '</div>';
									$output .= '</div>';
									
									$forms = $wpdb->get_results('SELECT Id, title FROM '.$wpdb->prefix.'wap_nex_forms ORDER BY Id DESC'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
										
										
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-2">';
											$output .= '<div class="report-setting-label">'.__('Reporting Form','nex-forms').'</div>';
										$output .= '</div>';
										$output .= '<div class="col-sm-10">';
											
											$output .= '<select name="form_selection" class="form-control form_selection" data-selected="'.$report_form.'">';
											$output .= '<option value="0" selected="selected">'.__('No Form Selected','nex-forms').'</option>';	
											foreach($forms as $form)
												{
												$total_entries = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')),$form->Id);	
												
												if($total_entries>0)	
													$output .= '<option class="reporting_item form_id_'.$form->Id.'" data-form-id="'.$form->Id.'" value="'.$form->Id.'">'.$form->title.' - '.$total_entries.' '.__('Entries','nex-forms').'</option>';	
												}
											$output .= '</select>';
													
										$output .= '</div>';
									$output .= '</div>';	
	
									
									
									$output .= '<div class="row">';
										$output .= '<div class="col-sm-2">';
										
										
										
										
										$output .= '</div>';
										$output .= '<div class="col-sm-10">';
											$output .= '<input type="button" class="form-control create_new_report btn blue "  value="SAVE">';
										$output .= '</div>';
									$output .= '</div>';
									
									
								
									
									
									
								$output .= '</div></div>';
							$output .= '</div>';
							
							$output .= '<div class="col-sm-1"></div>';
							$output .= '</div>';
						$output .= '</div>';
						
						$output .= '<div class="new-report-panel new-report panel-2">';
						
								if($get_report)
									{			
									$form_Id = $report_form;
									$get_form_fields = $wpdb->get_row($wpdb->prepare('SELECT title,field_details FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id=%d',sanitize_title($form_Id))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
									
									//$wpdb->show_errors();
									//$wpdb->print_errors();
									$set_form_fields = json_decode($get_form_fields->field_details,true);		
									$get_report_fields  = 	json_decode($get_report->report_fields,true);
											
											$output .= '<div class="report_field_row header">';
					
													$output .= '<div class="col-1">';
														$output .= '<input type="checkbox" name="toggel_field_selection" class="toggel_field_selection" checked="checked" value="">';
													$output .= '</div>';
													
													
													$output .= '<div class="col-2">';
														$output .= __('Field Name','nex-forms');
													$output .= '</div>';
													
													$output .= '<div class="col-3">';
														$output .= __('Database Column Name','nex-forms');
													$output .= '</div>';
												$output .= '</div>';
										
										$output .= '<div class="report_field_selection">';
										
										$output .= '<div class="report_field_row">';
											$output .= '<div class="col-1">';
												$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array('entry_id',$get_report_fields)) ? 'checked="checked"' : '').' value="entry_id">';
											$output .= '</div>';
											$output .= '<div class="col-2">';
												$output .= 'Entry Id';
											$output .= '</div>';
											
											$output .= '<div class="col-3">';
												$output .= 'entry_id';
											$output .= '</div>';
										$output .= '</div>';
										
										
												
										$output .= '<div class="report_field_row">';
											$output .= '<div class="col-1">';
												$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array('date_time',$get_report_fields)) ? 'checked="checked"' : '').' value="date_time">';
											$output .= '</div>';
											$output .= '<div class="col-2">';
												$output .= 'Date Time';
											$output .= '</div>';
											$output .= '<div class="col-3">';
												$output .= 'date_time';
											$output .= '</div>';
										$output .= '</div>';
										
										
										$records = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.$wpdb->prefix.'wap_nex_forms_entries` WHERE `nex_forms_Id`=%d ORDER BY `last_update` DESC LIMIT 500 OFFSET 0', sanitize_text_field($report_form))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
											foreach($records as $data)
												{
												$form_values = json_decode($data->form_data);
												
												foreach($form_values as $field)
													{
													$header_array[$field->field_name] = $nf_functions->format_column_name($field->field_name);
													}
												};
													
											$i = 0;
											foreach($header_array as $key=>$field)
												{
												
												if($field)
													{
													//if(in_array(
													$output .= '<div class="report_field_row">';
													
														$output .= '<div class="col-1">';
															$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array($nf_functions->format_column_name(str_replace('[]','',$field)),$get_report_fields)) ? 'checked="checked"' : '').' value="'.$nf_functions->format_column_name(str_replace('[]','',$field)).'">';
														$output .= '</div>';
														
														
														$output .= '<div class="col-2">';
															$output .= $nf_functions->unformat_name($key);
															$output .= '<input type="hidden" name="field_selection['.$i.'][field_name]" value="'.$nf_functions->format_column_name(str_replace('[]','',$field)).'">';
														$output .= '</div>';
														
														$output .= '<div class="col-3">';
															$output .= '<input type="text" class="form-control" name="field_selection['.$i.'][col_name]" maxlength="64" value="'.$nf_functions->format_column_name(str_replace('[]','',$field)).'">';
														$output .= '</div>';
													$output .= '</div>';
													}
												$i++;
												}
										
										
													
									/*$i = 0;
										foreach($set_form_fields as $key=>$field)
											{
											
											if($field['field_name'])
												{
												$output .= '<div class="report_field_row">';
												
													$output .= '<div class="col-1">';
														$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array($nf_function->format_column_name(str_replace('[]','',$field['field_name'])),$get_report_fields)) ? 'checked="checked"' : '').' value="'.$nf_function->format_column_name(str_replace('[]','',$field['field_name'])).'">';
													$output .= '</div>';
													
													
													$output .= '<div class="col-2">';
														$output .= $nf_function->unformat_name($field['field_name']);
														$output .= '<input type="hidden" name="field_selection['.$i.'][field_name]" value="'.$nf_function->format_column_name(str_replace('[]','',$field['field_name'])).'">';
													$output .= '</div>';
													
													$output .= '<div class="col-3">';
														$output .= '<input type="text" class="form-control" name="field_selection['.$i.'][col_name]" maxlength="64" value="'.$nf_function->format_column_name(str_replace('[]','',$field['field_name'])).'">';
													$output .= '</div>';
												$output .= '</div>';
												}
											$i++;
											}*/
											
										$output .= '</div>';	
									}
						
						$output .= '<div class="report_field_row footer">';
				$output .= '<div class="">';
					$output .= '<input type="button" class="form-control set_field_selection btn blue" value="Generate Report">';
				$output .= '</div>';
			$output .= '</div>';
						$output .= '</div>';
						
						
						$output .= '<div class="new-report-panel new-report panel-3">';
						
						
							if($get_report)
									{
									
									 
							
										$output .= '<div class="add_clause">';
											$output .= '<a class="nf_button aa_bg_sec_btn add_new_where_clause2"><i class="fa fa-plus"></i> Add Filter </a>';
											$output .= '<a class="nf_button aa_bg_sec_btn run_query_2 run_query" id="'.sanitize_text_field($report_form).'"><i class="fa fa-file-import"></i> Run Query </a>';
											$output .= '<div class="close_filters"><span class="fas fa-arrow-left"></span></div>';
										$output .= '</div>';
										
										$output .= '<div class="right-col-top">';
											
											$output .= $dashboard->report_get_additional_params(true,$report_id);
										
										$output .= '</div>';
									
									
									
									$output .= '<div class="right-bottom">';
									$output .= '</div>';
									
									}
						$output .= '</div>';
						
						
						$output .= '</div>';
					
					$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';
	
			if($do_ajax)
				{
				NEXForms_clean_echo($output);
				wp_die();
				}
			else
				return $output;
	
}
		
		
		public function create_report(){
			if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
				
			global $wpdb;
			
			
			$nf_function = new NEXForms_functions();	
			$nf_functions = new NEXForms_functions();			
			$table_name = $wpdb->prefix.'nex_forms_'.$nf_function->format_column_name(sanitize_title($_POST['report_title']));
			
			$update_id = sanitize_title($_POST['report_update_id']);
			
			
			$output = '';
			
			$tz = wp_timezone();
			$set_date = new DateTime("now", $tz);	
			
			if($update_id!=0)
				{
				$get_existing_table = $wpdb->get_var($wpdb->prepare('SELECT db_table FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id = %d',$update_id)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$update = $wpdb->update ( $wpdb->prefix.'wap_nex_forms_reports', array // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					(
					'report_title'=>sanitize_text_field($_POST['report_title']),
					'nex_forms_Id'=>sanitize_text_field($_POST['report_form']),
					'db_table'=>$table_name,
					'date_time'			=> $set_date->format('Y-m-d H:i:s'),
					), array(	'Id' => $update_id) );  // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					
				if($get_existing_table != $table_name)
					{
					if($wpdb->get_var("show tables like '".$get_existing_table."'")) // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						{
						$wpdb->query("RENAME TABLE `".$get_existing_table."` TO `".$table_name."`"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						}
					}
				$output .= '<div id="report-edit-Id" style="display:none;">'.$update_id.'</div>';
				}
			else
				{
				$table_exists = $wpdb->get_var($wpdb->prepare('SELECT db_table FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE db_table = %s',$table_name)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				if($table_exists)
					{
					NEXForms_clean_echo( 'Report <strong>'.sanitize_text_field($_POST['report_title']).'</strong> already exists. Please choose another report title.');
					wp_die();
					}
				$insert = $wpdb->insert ( $wpdb->prefix.'wap_nex_forms_reports',  array // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					(
					'report_title'=>sanitize_text_field($_POST['report_title']),
					'nex_forms_Id'=>sanitize_text_field($_POST['report_form']),
					'db_table'=>$table_name,
					'date_time'			=> $set_date->format('Y-m-d H:i:s'),
					'status'=>2
					) ); 
				$insert_id = $wpdb->insert_id;
				$output .= '<div id="report-edit-Id" style="display:none;">'.$insert_id.'</div>';
				}
					
			$form_Id = sanitize_title($_POST['report_form']);
			$get_form_fields = $wpdb->get_row($wpdb->prepare('SELECT title,field_details FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id=%d',sanitize_title($form_Id))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			//$wpdb->show_errors();
			//$wpdb->print_errors();
			$set_form_fields = json_decode($get_form_fields->field_details,true);
									
			/*if(!$set_form_fields)
				{
				$output .= '<div class="erro_msg_panel alert alert-danger">'.__('This form needs to be resaved to made ready for reporting 2.0','nex-forms').'<a href="'.get_admin_url().'admin.php?page=nex-forms-builder&open_form='.$form_Id.'" class="form_title"   title="Edit - '.$title.'" data-title="'.__('Edit Form','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom">'.__(' RE-SAVE ', 'nex-forms').$get_form_fields->title.' '.__('form now', 'nex-forms').'.</a></div>';	
				}*/
			
			//echo '<pre>';
			//print_r($set_form_fields);
			//echo '<pre>';
			
			$output .= '<div class="report_field_row header">';
					
						$output .= '<div class="col-1">';
							$output .= '<input type="checkbox" name="toggel_field_selection" class="toggel_field_selection" checked="checked" value="">';
						$output .= '</div>';
						
						
						$output .= '<div class="col-2">';
							$output .= __('Field Name','nex-forms');
						$output .= '</div>';
						
						$output .= '<div class="col-3">';
							$output .= __('Database Column Name','nex-forms');
						$output .= '</div>';
					$output .= '</div>';
			
			$output .= '<div class="report_field_selection">';
			
			$records = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.$wpdb->prefix.'wap_nex_forms_entries` WHERE `nex_forms_Id`=%d ORDER BY `last_update` DESC LIMIT 500 OFFSET 0', sanitize_text_field($_POST['report_form']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
			foreach($records as $data)
				{
				$form_values = json_decode($data->form_data);
				
				foreach($form_values as $field)
					{
					$header_array[$field->field_name] = $nf_functions->format_column_name($field->field_name);
					}
				};
			//$get_submitted_fields = json_decode($records['form_data'],true);
			//echo '####<pre>';
				//print_r($header_array);
			//echo '</pre>';
			
			$field_selection = $header_array;
			
			
			if($update_id!=0)
				{
				$get_field_selection = $wpdb->get_var($wpdb->prepare('SELECT report_fields FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id = %d',$update_id)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				
				$field_selection = json_decode($get_field_selection,true);
				
				}
			
			
			$output .= '<div class="report_field_row">';
				$output .= '<div class="col-1">';
					$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array('entry_id',$field_selection)) ? 'checked="checked"' : '').' value="entry_id">';
				$output .= '</div>';
				$output .= '<div class="col-2">';
					$output .= 'Entry Id';
				$output .= '</div>';
				
				$output .= '<div class="col-3">';
					$output .= 'entry_id';
				$output .= '</div>';
			$output .= '</div>';
			
			
					
			$output .= '<div class="report_field_row">';
				$output .= '<div class="col-1">';
					$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array('date_time',$field_selection)) ? 'checked="checked"' : '').' value="date_time">';
				$output .= '</div>';
				$output .= '<div class="col-2">';
					$output .= 'Date Time';
				$output .= '</div>';
				$output .= '<div class="col-3">';
					$output .= 'date_time';
				$output .= '</div>';
			$output .= '</div>';
			
			
			
			
			
			
			
			$i = 0;
			
			foreach($header_array as $key=>$field)
				{
				
				if($field)
					{
					//if(in_array(
					$output .= '<div class="report_field_row">';
					
						$output .= '<div class="col-1">';
							$output .= '<input type="checkbox" name="showhide_fields[]" '.((in_array($nf_functions->format_column_name(str_replace('[]','',$field)),$field_selection)) ? 'checked="checked"' : '').' value="'.$nf_function->format_column_name(str_replace('[]','',$field)).'">';
						$output .= '</div>';
						
						
						$output .= '<div class="col-2">';
							$output .= $nf_function->unformat_name($key);
							$output .= '<input type="hidden" name="field_selection['.$i.'][field_name]" value="'.$nf_function->format_column_name(str_replace('[]','',$field)).'">';
						$output .= '</div>';
						
						$output .= '<div class="col-3">';
							$output .= '<input type="text" class="form-control" name="field_selection['.$i.'][col_name]" maxlength="64" value="'.$nf_function->format_column_name(str_replace('[]','',$field)).'">';
						$output .= '</div>';
					$output .= '</div>';
					}
				$i++;
				}
			
			
			
			
			
			$output .= '</div>';
			
			$output .= '<div class="report_field_row footer">';
				$output .= '<div class="">';
					$output .= '<input type="button" class="form-control set_field_selection btn blue" value="Generate Report">';
				$output .= '</div>';
			$output .= '</div>';	
			
			NEXForms_clean_echo( $output);
			wp_die();
			
		}
		
		
		public function entries_menu(){
			
			global $wpdb;
			$output = '';	
			
			$nf_function = new NEXForms_functions();
	
			$database_actions = new NEXForms_Database_Actions();
			
			$forms = $wpdb->get_results('SELECT Id, title FROM '.$wpdb->prefix.'wap_nex_forms ORDER BY Id DESC'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			

			$total_all = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL'))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery 
			
			$additional_params = array(array('column'=>'viewed','operator'=>'IS','value'=>'NULL'), array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
			$total_unread = $database_actions->get_total_records('wap_nex_forms_entries',$additional_params);
				
			$output .= '<ul class="forms_menu aa_menu">';
				
				
				
				$output .= '<li class="form_item top_item dropable all_entries" data-form-id="0" data-folder="all_entries">';	
					$output .= '<a class="form_item all_entries main_item active" ><span class="menu_icon fas fa-inbox"></span><span class="form_title">'.__('Inbox','nex-forms').'</span><span class="form_entry_total"><span class="menu_badge">'.$total_all.'</span><span class="form_entry_unread">'.(($total_unread<=0) ? '' : '&nbsp;('.$total_unread.')' ).'</span></span></a>';
						
					//$output .= '<ul class="forms_menu aa_menu aa_bg_tri">';
				$output .= '</li>';	
				foreach($forms as $form)
					{
					$total_entries = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')),$form->Id);	
					
					$additional_params_form = array(array('column'=>'viewed','operator'=>'IS','value'=>'NULL'), array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
					$total_unread_form = $database_actions->get_total_records('wap_nex_forms_entries',$additional_params_form,$form->Id);
					
					if($total_entries>0)	
						$output .= '<li class="form_item   sub_form_item form_id_'.$form->Id.'" data-form-id="'.$form->Id.'" data-folder="form_entries_'.$form->Id.'"><a class="form_item form_entries_'.$form->Id.'"><span class="form_title">'.$form->title.'</span><span class="form_entry_total"><span class="menu_badge">'.$total_entries.'</span> <span class="form_entry_unread">'.(($total_unread_form>0) ? '&nbsp;('.$total_unread_form.')' : '' ).'</span></span></a></li>';	
					}
					
					//$output .= '</ul>';	
				
				
				/*$output .= '<li class="form_item top_item dropable entry_attachment" data-folder="entry_attachment">';	
					$output .= '<a class="form_item entry_attachment main_item" ><span class="menu_icon fas fa-paperclip"></span><span class="form_title">'.__('Attachments','nex-forms').'</span></a>';
				$output .= '</li>';
				
				$output .= '<li class="form_item top_item dropable starred_entries" data-folder="starred_entries">';	
					$output .= '<a class="form_item starred_entries main_item" ><span class="menu_icon fas fa-star"></span><span class="form_title">'.__('Starred','nex-forms').'</span></a>';
				$output .= '</li>';*/
				
				$output .= '<li class="form_item top_item dropable paypal_entries" data-folder="payment_entries">';	
					$output .= '<a class="form_item payment_entries main_item" ><span class="menu_icon fab fa-paypal"></span><span class="form_title">'.__('PayPal Payments','nex-forms').'</span></a>';
				$output .= '</li>';	
					//$output .= '<ul class="forms_menu aa_menu ">';
						
						$output .= '<li class="form_item sub_form_item  dropable paypal_entries_paid" data-folder="payment_entries_paid">';	
							$output .= '<a class="form_item form_item_sec payment_entries_paid" ><span class="menu_icon fas fa-check"></span><span class="form_title">'.__('Paid','nex-forms').'</span></a>';
						$output .= '</li>';
						
						$output .= '<li class="form_item sub_form_item  dropable paypal_entries_unpaid" data-folder="payment_entries_unpaid">';	
							$output .= '<a class="form_item form_item_sec payment_entries_unpaid" ><span class="menu_icon fas fa-times"></span><span class="form_title">'.__('Unpaid','nex-forms').'</span></a>';
						$output .= '</li>';
						
						$output .= '<li class="form_item sub_form_item  dropable paypal_entries_pending" data-folder="payment_entries_pending">';	
							$output .= '<a class="form_item form_item_sec payment_entries_pending" ><span class="menu_icon fas fa-sync-alt"></span><span class="form_title">'.__('Pending','nex-forms').'</span></a>';
						$output .= '</li>';
						
					//$output .= '</ul>';
				
				
				/*$output .= '<li class="form_item dropable archived_entries">';	
					$output .= '<a class="form_item archived_entries main_item"><span class="menu_icon fas fa-archive"></span><span class="form_title">'.__('Archived','nex-forms').'</span></a>';
				$output .= '</li>';*/
				
				$output .= '<li class="form_item top_item dropable trashed_entries" data-form-id="0" data-folder="trashed_entries">';	
					$output .= '<a class="form_item trashed_entries main_item"><span class="menu_icon fas fa-trash"></span><span class="form_title">'.__('Trash','nex-forms').'</span></a>';
				$output .= '</li>';
				
					
			$output .= '</ul>';
			
			
			$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';

			if($do_ajax)
				{
				NEXForms_clean_echo($output);
				wp_die();
				}
			else
				return $output;
				
		}
		
		
		public function uploads_menu(){
			
			global $wpdb;
			$output = '';	
			
			$nf_function = new NEXForms_functions();
	
			$database_actions = new NEXForms_Database_Actions();
			
			$forms = $wpdb->get_results('SELECT Id, title FROM '.$wpdb->prefix.'wap_nex_forms ORDER BY Id DESC'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			

			$total_all = $database_actions->get_total_records('wap_nex_forms_files',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')));
			
			
			$output .= '<ul class="forms_menu aa_menu">';
				
				
				
				$output .= '<li class="form_item top_item dropable all_entries" data-form-id="0" data-folder="all_entries">';	
					$output .= '<a class="form_item all_entries main_item active" ><span class="menu_icon fas fa-file-upload"></span><span class="form_title">'.__('All Files','nex-forms').'</span><span class="form_entry_total"><span class="menu_badge">'.$total_all.'</span><span class="form_entry_unread">'.((isset($total_unread) && $total_unread<=0) ? '' : '&nbsp;('.((isset($total_unread)) ? $total_unread : '').')' ).'</span></span></a>';
						
					//$output .= '<ul class="forms_menu aa_menu aa_bg_tri">';
				$output .= '</li>';	
				foreach($forms as $form)
					{
					$total_entries = $database_actions->get_total_records('wap_nex_forms_files',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')),$form->Id);	
					
					
					if($total_entries>0)	
						$output .= '<li class="form_item   sub_form_item form_id_'.$form->Id.'" data-form-id="'.$form->Id.'" data-folder="form_entries_'.$form->Id.'"><a class="form_item form_entries_'.$form->Id.'"><span class="form_title">'.$form->title.'</span><span class="form_entry_total"><span class="menu_badge">'.$total_entries.'</span></span></a></li>';	
					}
					
				
				
				
				
					
			$output .= '</ul>';
			$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';

			if($do_ajax)
				{
				NEXForms_clean_echo($output);
				wp_die();
				}
			else
				return $output;
				
		}
		
		
		public function reporting_menu(){
			
			global $wpdb;
			$output = '';	
			
			$nf_function = new NEXForms_functions();
	
			$database_actions = new NEXForms_Database_Actions();
			
			$forms = $wpdb->get_results('SELECT Id, title FROM '.$wpdb->prefix.'wap_nex_forms ORDER BY Id DESC'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			

			$total_all = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')));
			
			$additional_params = array(array('column'=>'viewed','operator'=>'IS','value'=>'NULL'), array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
			$total_unread = $database_actions->get_total_records('wap_nex_forms_entries',$additional_params);
			
			$output .= '<div class="menu_head aa_bg_sec font_color_1">Select form to create report</div>';
			$output .= '<ul class="forms_menu aa_menu">';
				
				
				
				
					
				
				foreach($forms as $form)
					{
					$total_entries = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')),$form->Id);	
					
					$additional_params_form = '';//array(array('column'=>'viewed','operator'=>'IS','value'=>'NULL'), array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
					$total_unread_form = $database_actions->get_total_records('wap_nex_forms_entries',$additional_params_form,$form->Id);
					
					if($total_entries>0)	
						$output .= '<li class="reporting_item form_id_'.$form->Id.'" data-form-id="'.$form->Id.'"><a class="form_item"><span class="form_title">'.$nf_function->view_excerpt2($form->title,20).'</span><span class="form_entry_total"><span class="menu_badge">'.(($total_unread_form>0) ? ''.$total_unread_form.'' : '' ).'</span></span></a></li>';	
					}
				
				
					
			$output .= '</ul>';
			$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';

			if($do_ajax)
				{
				NEXForms_clean_echo($output);
				wp_die();
				}
			else
				return $output;
				
		}
		
		
		
		public function analytics_menu(){
			
			global $wpdb;
			$output = '';	
			
			$nf_function = new NEXForms_functions();
	
			$database_actions = new NEXForms_Database_Actions();
			
			$forms = $wpdb->get_results('SELECT Id, title FROM '.$wpdb->prefix.'wap_nex_forms ORDER BY Id DESC'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			

			$total_all = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')));
			
			$additional_params = array(array('column'=>'viewed','operator'=>'IS','value'=>'NULL'), array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
			$total_unread = $database_actions->get_total_records('wap_nex_forms_entries',$additional_params);
			
			//$output .= '<div class="menu_head aa_bg_sec font_color_1">Select form to create report</div>';
			$output .= '<ul class="forms_menu aa_menu">';
				
				
				
				$output .= '<li class="analytics_item form_id_0" data-form-id="0"><a class="form_item active"><span class="form_title">All Forms</span></a></li>';	
					
				
				foreach($forms as $form)
					{
					$total_entries = $database_actions->get_total_records('wap_nex_forms_entries',array(array('column'=>'trashed','operator'=>'IS','value'=>'NULL')),$form->Id);	
					
					$additional_params_form = '';//array(array('column'=>'viewed','operator'=>'IS','value'=>'NULL'), array('column'=>'trashed','operator'=>'IS','value'=>'NULL'));
					$total_unread_form = $database_actions->get_total_records('wap_nex_forms_entries',$additional_params_form,$form->Id);
					
					//if($total_entries>0)	
						$output .= '<li class="analytics_item form_id_'.$form->Id.'" data-form-id="'.$form->Id.'"><a class="form_item"><span class="form_title">'.$nf_function->view_excerpt2($form->title,30).'</span></a></li>';	//<span class="form_entry_total"><span class="menu_badge">'.(($total_unread_form>0) ? ''.$total_unread_form.'' : '0' ).'</span></span>
					}
				
				
					
			$output .= '</ul>';
			$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';

			if($do_ajax)
				{
				NEXForms_clean_echo($output);
				wp_die();
				}
			else
				return $output;
				
		}
		
		public function delete_form_entry(){
			
			if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
				
			global $wpdb;

			$db_table = $wpdb->prepare('%s',esc_sql(sanitize_title($_POST['table'])));
			$db_table = str_replace('\'','',$db_table);
			
			if(!strstr($db_table, 'nex_forms'))
				wp_die();
			
			
			if($_POST['delete_action']=='trash')
				{		
				foreach($_POST['selection'] as $key=>$val)
					{
					$set_val = $wpdb->prepare('%d',esc_sql(sanitize_text_field($val)));
					$set_val = str_replace('\'','',$set_val);
					$update = $wpdb->update ( $wpdb->prefix . $db_table, array('trashed'=>'1'), array(	'Id'=>$set_val) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery	 
					
					}
				}
			else
				{
				foreach($_POST['selection'] as $key=>$val)
					{
					$set_val = $wpdb->prepare('%d',esc_sql(sanitize_text_field($val)));
					$set_val = str_replace('\'','',$set_val);
					$delete = $wpdb->delete($wpdb->prefix. $db_table,array('Id'=>$set_val)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					}
				}
			//$wpdb->show_errors();
			//$wpdb->print_errors();
			die();
		}	
		
		
		public function restore_records(){
			
			if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global  $wpdb;
				foreach($_POST['selection'] as $key=>$val)
					{
					$set_val = $wpdb->prepare('%d',esc_sql(sanitize_text_field($val)));
					$set_val = str_replace('\'','',$set_val);
					$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries', array('trashed'=>NULL), array(	'Id' => $set_val) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					NEXForms_clean_echo( $update);
					}

			wp_die();	
		}
		
		
		public function set_starred(){
			
			if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global  $wpdb;
			
			$set_starred = ($_POST['starred']=='1' || $_POST['starred']==1) ? 0 : 1;
			if($_POST['record_id'])
				{
				$record_id = $wpdb->prepare('%d',esc_sql(sanitize_text_field($_POST['record_id'])));
				$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries', array('starred'=>$set_starred), array(	'Id' => $record_id) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
			else
				{
				foreach($_POST['selection'] as $key=>$val)
					{
					$set_val = $wpdb->prepare('%d',esc_sql(sanitize_text_field($val)));
					$set_val = str_replace('\'','',$set_val);
					$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries', array('starred'=>$set_starred), array(	'Id' => $set_val) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					}
				}
			
			
			wp_die();	
		}
		
		public function set_read(){
			
			if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			
			global  $wpdb;
			
			$set_read = ($_POST['read']!='1') ? NULL : 'viewed';
			
			foreach($_POST['selection'] as $key=>$val)
				{
				$set_val = $wpdb->prepare('%d',esc_sql(sanitize_text_field($val)));
				$set_val = str_replace('\'','',$set_val);
				$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries', array('viewed'=>$set_read), array(	'Id' => $set_val) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
			wp_die();	
		}
		
		public function dashboard_checkout()
			{
			$db_action = new NEXForms_Database_Actions();
			$this->checkout	= $db_action->checkout();
			$this->client_info	= $db_action->client_info;
			$this->license_info	= $db_action->license_info;	
			
			}
		public function remove_unwanted_styles(){
			
			
			
			$other_config = get_option('nex-forms-other-config');
			$zero_conflict = isset($other_config['zero-con']) ? $other_config['zero-con'] : '1';
			if($zero_conflict=='1')
				{
				$dashboard = new NEXForms_dashboard();
				$dashboard->dashboard_checkout();
				global $wp_styles;
				$include_style_array = array('colors','common','wp-codemirror', 'wp-theme-plugin-editor','forms','admin-menu','dashboard','list-tables','bootstrap-timepicker','jqui-timepicker','bootstrap-material-datetimepicker','nf-nouislider','nf-jquery-ui','nf-md-checkbox-radio','edit','revisions','media','themes','about','nav-menus','widgets','site-icon','l10n','wp-admin','login','install','wp-color-picker','customize-controls','customize-widgets','customize-nav-menus','press-this','ie','buttons','dashicons','open-sans','admin-bar','wp-auth-check','editor-buttons','media-views','wp-pointer','customize-preview','wp-embed-template-ie','imgareaselect','wp-jquery-ui-dialog','mediaelement','wp-mediaelement','thickbox','deprecated-media','farbtastic','jcrop','colors-fresh','nex-forms-jQuery-UI','nex-forms-font-awesome','nex-forms-bootstrap','nex-forms-fields','nex-forms-ui','nex-forms-admin-style','nex-forms-animate','nex-forms-admin-overrides','nex-forms-admin-bootstrap.colorpickersliders','nex-forms-public-admin','nex-forms-editor','nex-forms-custom-admin','nex-forms-jq-ui','nf-styles-chosen','nf-admin-color-adapt', 'nex-forms-jq-ui','nf-styles-font-menu', 'nex-forms-bootstrap-tour.min','nf-color-adapt-fresh','nf-color-adapt-light','nf-color-adapt-blue','nf-color-adapt-coffee','nf-color-adapt-ectoplasm','nf-color-adapt-midnight','nf-color-adapt-ocean','nf-color-adapt-sunrise', 'nf-color-adapt-default','nex_forms-materialize.min','nex_forms-bootstrap.min','nex_forms-dashboard','nex_forms-font-awesome-5','nex_forms-font-awesome-4-shims','nex_forms-material-icons','ion.rangeSlider','ion.rangeSlider.skinFlat','nex_forms-builder','google-roboto');
			
				NEXForms_clean_echo( '<div class="unwanted_css_array" style="display:none;">');
				foreach($wp_styles->registered as $wp_style=>$array)
					{
					if(!in_array($array->handle,$include_style_array) && !strstr($array->handle,'nex-forms'))
						{
						NEXForms_clean_echo( '<div class="unwanted_css">'.$array->handle.'-css</div>');
						}
					}	
				NEXForms_clean_echo( '</div>');
			
				}
				
		}
		
		
		public function new_menu($page_title=''){
			
			$config = new NEXForms5_Config();
			$dashboard = new NEXForms_dashboard();
			$theme = wp_get_theme();
			
			$dashboard->dashboard_checkout();
			
			
			
			$output = '';
			$output .= '<div class="nf-header">';
			
			if(!$dashboard->checkout)
				{
				$output .= '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=dashboard_activation" target="_blank" class="logo"></a>';	
				}
			else
				{
			if ( nf_fs()->can_use_premium_code() )
				{
				$output .= '<a href="https://basixonline.net/" target="_blank" class="logo"></a>';	
				}
			else
				{
				$license_info = $dashboard->license_info;
				$supported_until = $license_info['supported_until'];
				$supported_date = new DateTime($supported_until);
				$now = new DateTime();
				if ($supported_date < $now)
						{
						$output .= '<a href="https://basixonline.net/nex-forms/pricing-comparison-envato-vs-SaaS/?promo=1" target="_blank" class="logo"></a>';
						}
					else
						{
						$output .= '<a href="https://basixonline.net/nex-forms-wordpress-form-builder-demo/" target="_blank" class="logo"></a>';	
						}
				}
				}
							$output .= '<div class="version">v<strong>'.$config->plugin_version.'</strong></div>
							
							<div class="dashboard-top-menu">
								<div class="item">
									<a href="https://basixonline.net/nex-forms-docs/" target="_blank"><span class="fas fa-graduation-cap"></span>Documentation</a>
								</div>';
							if(!$dashboard->checkout)
								{
								$output .= '
									<div class="item">
										<a href="https://basix.ticksy.com/" target="_blank"><span class="fas fa-life-ring"></span>Support</a>
									</div>';	
								}
							else
								{
							if ( nf_fs()->can_use_premium_code() )
								{
								$output .= '
									<div class="item">
										<a href="https://basix.ticksy.com/" target="_blank"><span class="fas fa-life-ring"></span>Support</a>
									</div>';	
								}
							else
								{
								$license_info = $dashboard->license_info;
								$supported_until = $license_info['supported_until'];
								$supported_date = new DateTime($supported_until);
								$now = new DateTime();
								if ($supported_date < $now)
									{
									$output .= '
										<div class="item">
											<a target="_blank" class="sup_ex txt-red"><i class="fas fa-warning txt-red"></i>&nbsp;Support</a>
										</div>';					
									}
								else
									{
									$output .= '
									<div class="item">
										<a href="https://basix.ticksy.com/" target="_blank"><span class="fas fa-life-ring"></span>Support</a>
									</div>';	
									}
								}						
								}						
								
								if(!$dashboard->checkout)
									{
									$output .= '<div class="item buy-now">
										<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=menu">Upgrade to PREMIUM</a>
									</div>';
									}
							if(function_exists('nf_fs'))
								{
								if ( nf_fs()->is_trial() ) {
									$site = nf_fs()->get_site();
									$trialEnds = $site->trial_ends;
									// Convert trial end date to DateTime object
									$trial_end_date = new DateTime($trialEnds);
									$today = new DateTime();
									// Calculate the difference
									$interval = $today->diff($trial_end_date);
									// Check if trial is still active
									
									$days = $interval->d;
									$hours = $interval->h;
									$minutes = $interval->i;
									
									if ($trial_end_date > $today) {
										//$output .= '<div class="item trial-period"><a>Trial ends in&nbsp;<strong>' . ($days-1) . '</strong>&nbsp;day'.(($days>1) ? 's' : '').' '.$hours.' hrs and '.$minutes.' min</a></div>';
									} else {
										//$output .= '<div class="item trial-period trial-end">Trial has ended</div>.';
									}
								}
								}
							
				$output .= '</div>';
				
			$output .= '</div>';
			
			$output .= '<div class="dash-left-col">';
				$output .= '<ul class="aa_menu">';
								
									
										$output .= '<li class="db_tab '.(($_REQUEST['page']=='nex-forms-dashboard') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-dashboard" data-title="'.__('Dashboard','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-dashboard') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-home"></span> <span class="menu-text">'.__('Dashboard','nex-forms').'</span></a></li>';
										
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions"  data-placement="bottom" data-title="'.__('Form Entries','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-inbox"></span> <span class="menu-text">'.__('Form Entries','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-submission-reporting') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-submission-reporting"  data-placement="bottom" data-title="'.__('Reporting','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-submission-reporting') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-database"></span> <span class="menu-text">'.__('Reporting','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-analytics"  data-placement="bottom" data-title="'.__('Analytics','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-chart-bar"></span> <span class="menu-text">'.__('Analytics','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-file-uploads') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-file-uploads"  data-placement="bottom" data-title="'.__('File Uploads','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-file-uploads') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-file-upload"></span> <span class="menu-text">'.__('File Uploads','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'current' : '').'" style="position: absolute;bottom: 90px;"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-global-settings"  data-placement="bottom" data-title="'.__('Global Settings','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-cog"></span> <span class="menu-text">'.__('Settings','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'current' : '').'" style="position: absolute;bottom: 144px;"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-add-ons"  data-placement="bottom" data-title="'.__('Add-ons','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-plug"></span> <span class="menu-text">'.__('Add-ons','nex-forms').'</span></a></li>';
								
								
							   $output .= '</ul>';
				
			  
			  $output .= '</div>';
			  
			  
			  $output .= '<div class="dash-right-col">';
			  $output .= '<div class="row row_zero_margin ">';
			
			
			return $output;
				
		}
		
		
		public function dashboard_menu($page_title){
				
				$item = get_option('7103891');
				
				$output = '';
				$config = new NEXForms5_Config();
				$nf_function = new NEXForms_Functions();	
			
				//$output .= $nf_function->new_form_setup($this->checkout);
				
			   
				$theme = wp_get_theme();
				
				$set_folder = isset($_REQUEST['folder']) ? sanitize_text_field($_REQUEST['folder']) : 0;
				$entry_id = isset($_REQUEST['entry_id']) ? sanitize_text_field($_REQUEST['entry_id']) : 0;
				
				$output .= '<div class="set_entry_id" style="display:none;">'.$entry_id.'</div>';
				$output .= '<div class="set_folder" style="display:none;">'.$set_folder.'</div>';
				
				$output .= '<div class="hidden">';
				  $output .= '<div id="siteurl">'.get_option('siteurl').'</div>';
				  $output .= '<div id="nf_dashboard_load">0</div>';
				  $output .= '<div id="plugins_url">'.plugins_url('/',__FILE__).'</div>';
				  $output .= '<div id="load_entry">'.$this->checkout.'</div>';
				  $output .= '<div id="current_form_id">0</div>';
				  $output .= '<div id="currently_viewing" style="display:none;">'.(($this->checkout) ? 'dashboard' : 'backend').'</div>';
			  	$output .= '</div>';
				
				$output .= '<nav class="start-page aa_bg_main">';
					$output .= '<div class="nav-container prime-menu">';
						
						$output .= '<div class="inner">';
							$output .= '<ul class="navigation aa_menu">';
								$output .= '<li class="logo-wrapper"><a href="'.get_admin_url().'admin.php?page=nex-forms-dashboard" class="logo"> NEX-Forms </a>';//
								$output .= '</li>';	
							 $output .= '</ul>';	
							$output .= '<ul class="navigation aa_menu db_tabs_nf">';
								//$output .= '<li class=" menu-item-has-children"><a href="" class="logo create_new_form_home"></a>';//
									/*$output .= '<ul class="aa_menu_2">';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-dashboard" class="'.(($_REQUEST['page']=='nex-forms-dashboard') ? 'active' : '').' submissions_tab"><span class="fas fa-home"></span> '.__('Dashboard','nex-forms').'</a></li>';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions" class="'.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'active' : '').' submissions_tab"><span class="fas fa-envelope"></span> '.__('Form Entries','nex-forms').'</a></li>';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-page-reporting" class="'.(($_REQUEST['page']=='nex-forms-page-reporting') ? 'active' : '').' submissions_tab"><span class="fas fa-scroll"></span> '.__('Reporting','nex-forms').'</a></li>';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-page-analytics" class="'.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'active' : '').' submissions_tab"><span class="fas fa-chart-line"></span> '.__('Analytics','nex-forms').'</a></li>';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-page-file-uploads" class="'.(($_REQUEST['page']=='nex-forms-page-file-uploads') ? 'active' : '').' submissions_tab"><span class="fas fa-file-upload"></span> '.__('File Uploads','nex-forms').'</a></li>';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-page-global-settings" class="'.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'active' : '').' submissions_tab"><span class="fas fa-cog"></span> '.__('Settings','nex-forms').'</a></li>';
										$output .= '<li class=""><a href="'.get_admin_url().'admin.php?page=nex-forms-page-add-ons" class="'.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'active' : '').' submissions_tab"><span class="fas fa-plug"></span> '.__('Add-ons','nex-forms').'</a></li>';
									$output .= '</ul>';*/
								$output .= '</li>';
									
										$output .= '<li class="db_tab '.(($_REQUEST['page']=='nex-forms-dashboard') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-dashboard" data-title="'.__('Dashboard','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-dashboard') ? 'current' : '').' submissions_tab"><span class="fas fa-home"></span> <span class="menu-text">'.__('Dashboard','nex-forms').'</span></a></li>';
										
										//$output .= '<li class="db_tab menu-item-has-children"><a class="create_new_form_home" data-title="'.__('Create a NEW Form','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"><span class="fas fa-file-medical"></span></a></li>';
							   
										
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions"  data-placement="bottom" data-title="'.__('Form Entries','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-inbox"></span> <span class="menu-text">'.__('Form Entries','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-reporting') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-reporting"  data-placement="bottom" data-title="'.__('Reporting','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-reporting') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-database"></span> <span class="menu-text">'.__('Reporting','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-analytics"  data-placement="bottom" data-title="'.__('Analytics','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-chart-line"></span> <span class="menu-text">'.__('Analytics','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-file-uploads') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-file-uploads"  data-placement="bottom" data-title="'.__('File Uploads','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-file-uploads') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-file-upload"></span> <span class="menu-text">'.__('File Uploads','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-global-settings"  data-placement="bottom" data-title="'.__('Global Settings','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-cog"></span> <span class="menu-text">'.__('Settings','nex-forms').'</span></a></li>';
										$output .= '<li class="db_tab menu-item-has-children '.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-add-ons"  data-placement="bottom" data-title="'.__('Add-ons','nex-forms').'" class="'.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'current' : '').' submissions_tab"><span class="top-icon fas fa-plug"></span> <span class="menu-text">'.__('Add-ons','nex-forms').'</span></a></li>';
								
								
							  $output .= ($theme->Name=='NEX-Forms Demo' || !$this->checkout) ? '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock"" target="_blank" class="btn waves-effect waves-light upgrade_pro">BUY NEX-FORMS</a>' : '';
							   $output .= '</ul>';
							   //$output .= '<div class="page-title aa_font_color_default">'.$page_title.'</div>';
							
							
							$output .= '<div class="nf_version font_color_1"><span class="">Version '.$config->plugin_version.'</span></div>';
							   
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</nav>';
			
			return $output;
		}
		
		public function dashboard_header(){
				$item = get_option('7103891');
				
				$output = '';
				$config = new NEXForms5_Config();
				$nf_function = new NEXForms_Functions();
				//$builder = new NEXForms_Builder7();
					
				$output .= $nf_function->new_form_setup($this->checkout);
				
			   
				$theme = wp_get_theme();
				$output .= '<div id="demo_site" style="display:none;">'.(($theme->Name=='NEX-Forms Demo') ? 'yes' : 'no').'</div>';
				$output .= '<div id="currently_viewing" style="display:none;">'.(($this->checkout) ? 'dashboard' : 'backend').'</div>';
				
				$output .= '<div class="row row_zero_margin">';
					
					$output .= '
						<div class="col-sm-12">
						  <nav class="nav-extended dashboard_nav aa_bg_main prime-menu main_nav">
							
							<div class="nav-content aa_bg_main">
							 
							  <ul class="tabs_nf  aa_bg_main aa_menu">';
							  	
								$output .= ' <li class="tab logo"><img src="'. plugins_url( '/admin/css/'.NF_PATH.'images/logo.png',dirname(dirname(__FILE__))).'" alt=""><span class="version_number">v '.$config->plugin_version.'</li> '; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
							  	
								$output .= '<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-dashboard') ? 'current' : '').'"><a class="'.(($_REQUEST['page']=='nex-forms-dashboard') ? 'active' : '').' forms_tab" href="'.get_admin_url().'admin.php?page=nex-forms-dashboard"><span class="top-icon fa fas fa-home"></span><span class="menu-text">'.__('Dashboard','nex-forms').'</span></a></li>
								<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions" class="'.(($_REQUEST['page']=='nex-forms-page-submissions') ? 'active' : '').' submissions_tab"><span class="top-icon fa fas fa-envelope"></span><span class="menu-text">'.__('Submissions','nex-forms').'</span></a></li>
								<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-analytics" class="'.(($_REQUEST['page']=='nex-forms-page-analytics') ? 'active' : '').' submissions_tab"><span class="top-icon fa fas fa-chart-line"></span><span class="menu-text">'.__('Analytics','nex-forms').'</span></a></li>';
								if(function_exists('run_nf_adv_paypal') && $theme->Name!='NEX-Forms Demo')
									$output .= '<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-payments') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-payments" class="payment_tab '.(($_REQUEST['page']=='nex-forms-page-payments') ? 'active' : '').'"><span class="top-icon fa fas fa-funnel-dollar"></span><span class="menu-text">'.__('Payments','nex-forms').'</span></a></li>';

								
								
								$output .= '
								<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-reporting') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-reporting" class="reporting_tab '.(($_REQUEST['page']=='nex-forms-page-reporting') ? 'active' : '').'"><span class="top-icon fa fas fa-scroll"></span><span class="menu-text">'.__('Reporting','nex-forms').'</span></a></li>
								<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-attachments') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-attachments" class="file_uploads_tab '.(($_REQUEST['page']=='nex-forms-page-attachments') ? 'active' : '').'"><span class="top-icon fa fas fa-paperclip"></span><span class="menu-text">'.__('File Uploads','nex-forms').'</span></a></li>
								<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-global-settings" class="global_settings_tab '.(($_REQUEST['page']=='nex-forms-page-global-settings') ? 'active' : '').'"><span class="top-icon fa fas fa-cog"></span><span class="menu-text">'.__('Global Settings','nex-forms').'</span></a></li>
								<li class="tab has_icon '.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'current' : '').'"><a href="'.get_admin_url().'admin.php?page=nex-forms-page-add-ons" class="add_ons_tab '.(($_REQUEST['page']=='nex-forms-page-add-ons') ? 'active' : '').'"><span class="top-icon fa fas fa-puzzle-piece"></span><span class="menu-text">'.__('ADD-ONS','nex-forms').'</span></a></li>
								<li class="tab has_icon"><a href="http://basixonline.net/nex-forms-docs/" target="_blank"><span class="top-icon fa fas fa-file-export"></span><span class="menu-text">'.__('DOCS','nex-forms').'</span></a></li>
								'.(($theme->Name=='NEX-Forms Demo' || !$this->checkout) ? '<a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock"" target="_blank" class="btn waves-effect waves-light upgrade_pro">BUY NEX-FORMS</a>' : '' ).'
							  </ul>
							</div>
						  </nav>
						</div>'; 
				
				$output .= '</div>';
				
				return $output;
		}	
		
		public function form_analytics($print_chart=''){
			
			global $wpdb;
			
			$output = '';
			
			if($print_chart!='summary')
						{
					$output .= '<div class="controls">';
						
						
						
						
							$output .= '<div class="switch_chart active" data-chart-type="line"><i class="fa fa-line-chart"></i></div>';
						$output .= '<div class="switch_chart" data-chart-type="bar"><i class="fa fa-bar-chart"></i></div>';
						$output .= '<div class="switch_chart" data-chart-type="doughnut"><i class="fa fa-pie-chart"></i></div>';
						$output .= '<div class="switch_chart" data-chart-type="polarArea"><i class="fa fa-bullseye"></i></div>';
						$output .= '<div class="switch_chart" data-chart-type="radar"><i class="fa fa-spider"></i></div>';
						
						
					$output .= '</div>';
				}
			
			$output .= '<div class="dashboard-box form_analytics '.(($print_chart=='summary') ? 'summary_stats' : '' ).'">';
			
			if(($print_chart=='summary'))
				{
				$output .= '<div class="dashboard-box-header '.(($this->color_adapt) ? 'aa_bg_main': '' ).'">';
					
						$output .= '<div class="table_title '.(($this->color_adapt) ? 'font_color_1': '' ).'">'.__('Form Entry Analytics for the last 7 Days','nex-forms').'</div>';
				}
					
				if(($print_chart=='summary'))
				$output .= '</div>';
				
				
				
				
					
				
				$output .= '<div  class="dashboard-box-content">';
				
				
					
					$output .= '<div class="chart-container"><div class="data_set">'.$this->print_chart($this->checkout, $print_chart).'</div>
					
					<canvas id="chart_canvas" class="'.$print_chart.'"></canvas>
					</div>';
					
					
					
					
					
					$output .= '</div>';
					
					if($print_chart=='summary')
						{
						$output .='<div class="chart_legend">';
							$output .= '<a href="'.get_admin_url().'admin.php?page=nex-forms-page-analytics" class="more_button">More Insights <span class="fa fa-arrow-right"></span></a>';
						$output .= '</div>';
						}
				$output .= '</div>';
			
			return $output;
		}	
		
		public function print_chart($args='', $chart_view=''){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			$current_year = (int)date('Y');
					
					$set_chart_view = (isset($_REQUEST['is_summary'])) ? $_REQUEST['is_summary'] : '';
					
					if($set_chart_view=='summary')
						$chart_view = 'summary';
					
					$year_selected = isset($_REQUEST['year_selected']) ? $wpdb->prepare('%s',esc_sql(sanitize_text_field($_REQUEST['year_selected']))) : (int)date('Y');
					$year_selected = str_replace('\'','',$year_selected);
					$month_selected =  isset($_REQUEST['month_selected']) ? $wpdb->prepare('%s',esc_sql(sanitize_text_field($_REQUEST['month_selected']))) : (int)date('m');
					$month_selected = str_replace('\'','',$month_selected);
					
					$month_array = array('1'=>__('January','nex-forms'),'2'=>__('February','nex-forms'),'3'=>__('March','nex-forms'),'4'=>__('April','nex-forms'),'5'=>__('May','nex-forms'),'6'=>__('June','nex-forms'),'7'=>__('July','nex-forms'),'8'=>__('August','nex-forms'),'9'=>__('September','nex-forms'),'10'=>__('October','nex-forms'),'11'=>__('November','nex-forms'),'12'=>__('December','nex-forms'));
					
					$today = (int)date('j');
					
					$days_back = 1;
					if($chart_view=='summary')
						$days_back = ($today-7);
					
					
					if($year_selected)
						$current_year = $year_selected;
					
					$database_actions = new NEXForms_Database_Actions();
					$nf7_functions = new NEXForms_Functions();
					
					if($args)
						$checkin = $args;
					else
						$checkin = $database_actions->checkout();
					
					$form_id = isset($_REQUEST['form_id']) ? sanitize_title($_REQUEST['form_id']) : '';
					
					
					$where_str = 'Id <> 0';
						
					if($form_id)
					 	$where_str .= ' AND nex_forms_Id = '.$form_id.' ';
					
					if($chart_view=='summary')
						$where_str .= ' AND date_time >= DATE(NOW()) - INTERVAL 7 DAY';
					else
						{
						if($month_selected=='0')
							$where_str .= ' AND YEAR(date_time)=YEAR("'.$current_year.'-'.$month_selected.'-01")';
						else
							$where_str .= ' AND YEAR(date_time)=YEAR("'.$current_year.'-'.$month_selected.'-01") AND Month(date_time)= Month("'.$current_year.'-'.$month_selected.'-01")';
						}
					
					$form_entries = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE '.$where_str); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$form_views = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_views WHERE '.$where_str); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$form_interactions = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_stats_interactions WHERE '.$where_str); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					
					
						
					$submit_array 				= array();
					$view_array 				= array();
					$interaction_array 			= array();
					$submit_array_pm 			= array();
					$view_array_pm 				= array();
					$interaction_array_pm 		= array();
					$country_array 				= array(
													'AF' => __('Afghanistan','nex-forms'),
													'AX' => __('Aland Islands','nex-forms'),
													'AL' => __('Albania','nex-forms'),
													'DZ' => __('Algeria','nex-forms'),
													'AS' => __('American Samoa','nex-forms'),
													'AD' => __('Andorra','nex-forms'),
													'AO' => __('Angola','nex-forms'),
													'AI' => __('Anguilla','nex-forms'),
													'AQ' => __('Antarctica','nex-forms'),
													'AG' => __('Antigua and Barbuda','nex-forms'),
													'AR' => __('Argentina','nex-forms'),
													'AM' => __('Armenia','nex-forms'),
													'AW' => __('Aruba','nex-forms'),
													'AU' => __('Australia','nex-forms'),
													'AT' => __('Austria','nex-forms'),
													'AZ' => __('Azerbaijan','nex-forms'),
													'BS' => __('Bahamas the','nex-forms'),
													'BH' => __('Bahrain','nex-forms'),
													'BD' => __('Bangladesh','nex-forms'),
													'BB' => __('Barbados','nex-forms'),
													'BY' => __('Belarus','nex-forms'),
													'BE' => __('Belgium','nex-forms'),
													'BZ' => __('Belize','nex-forms'),
													'BJ' => __('Benin','nex-forms'),
													'BM' => __('Bermuda','nex-forms'),
													'BT' => __('Bhutan','nex-forms'),
													'BO' => __('Bolivia','nex-forms'),
													'BA' => __('Bosnia and Herzegovina','nex-forms'),
													'BW' => __('Botswana','nex-forms'),
													'BV' => __('Bouvet Island (Bouvetoya)','nex-forms'),
													'BR' => __('Brazil','nex-forms'),
													'IO' => __('British Indian Ocean Territory (Chagos Archipelago)','nex-forms'),
													'VG' => __('British Virgin Islands','nex-forms'),
													'BN' => __('Brunei Darussalam','nex-forms'),
													'BG' => __('Bulgaria','nex-forms'),
													'BF' => __('Burkina Faso','nex-forms'),
													'BI' => __('Burundi','nex-forms'),
													'KH' => __('Cambodia','nex-forms'),
													'CM' => __('Cameroon','nex-forms'),
													'CA' => __('Canada','nex-forms'),
													'CV' => __('Cape Verde','nex-forms'),
													'KY' => __('Cayman Islands','nex-forms'),
													'CF' => __('Central African Republic','nex-forms'),
													'TD' => __('Chad','nex-forms'),
													'CL' => __('Chile','nex-forms'),
													'CN' => __('China','nex-forms'),
													'CX' => __('Christmas Island','nex-forms'),
													'CC' => __('Cocos (Keeling) Islands','nex-forms'),
													'CO' => __('Colombia','nex-forms'),
													'KM' => __('Comoros the','nex-forms'),
													'CD' => __('Congo - Kinshasa','nex-forms'),
													'CG' => __('Congo - Brazzaville','nex-forms'),
													'CK' => __('Cook Islands','nex-forms'),
													'CR' => __('Costa Rica','nex-forms'),
													'CI' => __('CI','nex-forms'),
													'HR' => __('Croatia','nex-forms'),
													'CU' => __('Cuba','nex-forms'),
													'CY' => __('Cyprus','nex-forms'),
													'CZ' => __('Czech Republic','nex-forms'),
													'DK' => __('Denmark','nex-forms'),
													'DJ' => __('Djibouti','nex-forms'),
													'DM' => __('Dominica','nex-forms'),
													'DO' => __('Dominican Republic','nex-forms'),
													'EC' => __('Ecuador','nex-forms'),
													'EG' => __('Egypt','nex-forms'),
													'SV' => __('El Salvador','nex-forms'),
													'GQ' => __('Equatorial Guinea','nex-forms'),
													'ER' => __('Eritrea','nex-forms'),
													'EE' => __('Estonia','nex-forms'),
													'ET' => __('Ethiopia','nex-forms'),
													'FO' => __('Faroe Islands','nex-forms'),
													'FK' => __('Falkland Islands (Malvinas)','nex-forms'),
													'FJ' => __('Fiji the Fiji Islands','nex-forms'),
													'FI' => __('Finland','nex-forms'),
													'FR' => __('France','nex-forms'),
													'GF' => __('French Guiana','nex-forms'),
													'PF' => __('French Polynesia','nex-forms'),
													'TF' => __('French Southern Territories','nex-forms'),
													'GA' => __('Gabon','nex-forms'),
													'GM' => __('Gambia the','nex-forms'),
													'GE' => __('Georgia','nex-forms'),
													'DE' => __('Germany','nex-forms'),
													'GH' => __('Ghana','nex-forms'),
													'GI' => __('Gibraltar','nex-forms'),
													'GR' => __('Greece','nex-forms'),
													'GL' => __('Greenland','nex-forms'),
													'GD' => __('Grenada','nex-forms'),
													'GP' => __('Guadeloupe','nex-forms'),
													'GU' => __('Guam','nex-forms'),
													'GT' => __('Guatemala','nex-forms'),
													'GG' => __('Guernsey','nex-forms'),
													'GN' => __('Guinea','nex-forms'),
													'GW' => __('Guinea-Bissau','nex-forms'),
													'GY' => __('Guyana','nex-forms'),
													'HT' => __('Haiti','nex-forms'),
													'HM' => __('Heard Island and McDonald Islands','nex-forms'),
													'VA' => __('Holy See (Vatican City State)','nex-forms'),
													'HN' => __('Honduras','nex-forms'),
													'HK' => __('Hong Kong','nex-forms'),
													'HU' => __('Hungary','nex-forms'),
													'IS' => __('Iceland','nex-forms'),
													'IN' => __('India','nex-forms'),
													'ID' => __('Indonesia','nex-forms'),
													'IR' => __('Iran','nex-forms'),
													'IQ' => __('Iraq','nex-forms'),
													'IE' => __('Ireland','nex-forms'),
													'IM' => __('Isle of Man','nex-forms'),
													'IL' => __('Israel','nex-forms'),
													'IT' => __('Italy','nex-forms'),
													'JM' => __('Jamaica','nex-forms'),
													'JP' => __('Japan','nex-forms'),
													'JE' => __('Jersey','nex-forms'),
													'JO' => __('Jordan','nex-forms'),
													'KZ' => __('Kazakhstan','nex-forms'),
													'KE' => __('Kenya','nex-forms'),
													'KI' => __('Kiribati','nex-forms'),
													'KP' => __('North Korea','nex-forms'),
													'KR' => __('South Korea','nex-forms'),
													'KW' => __('Kuwait','nex-forms'),
													'KG' => __('Kyrgyzstan','nex-forms'),
													'LA' => __('Lao','nex-forms'),
													'LV' => __('Latvia','nex-forms'),
													'LB' => __('Lebanon','nex-forms'),
													'LS' => __('Lesotho','nex-forms'),
													'LR' => __('Liberia','nex-forms'),
													'LY' => __('Libya','nex-forms'),
													'LI' => __('Liechtenstein','nex-forms'),
													'LT' => __('Lithuania','nex-forms'),
													'LU' => __('Luxembourg','nex-forms'),
													'MO' => __('Macao','nex-forms'),
													'MK' => __('Macedonia','nex-forms'),
													'MG' => __('Madagascar','nex-forms'),
													'MW' => __('Malawi','nex-forms'),
													'MY' => __('Malaysia','nex-forms'),
													'MV' => __('Maldives','nex-forms'),
													'ML' => __('Mali','nex-forms'),
													'MT' => __('Malta','nex-forms'),
													'MH' => __('Marshall Islands','nex-forms'),
													'MQ' => __('Martinique','nex-forms'),
													'MR' => __('Mauritania','nex-forms'),
													'MU' => __('Mauritius','nex-forms'),
													'YT' => __('Mayotte','nex-forms'),
													'MX' => __('Mexico','nex-forms'),
													'FM' => __('Micronesia','nex-forms'),
													'MD' => __('Moldova','nex-forms'),
													'MC' => __('Monaco','nex-forms'),
													'MN' => __('Mongolia','nex-forms'),
													'ME' => __('Montenegro','nex-forms'),
													'MS' => __('Montserrat','nex-forms'),
													'MA' => __('Morocco','nex-forms'),
													'MZ' => __('Mozambique','nex-forms'),
													'MM' => __('Myanmar','nex-forms'),
													'NA' => __('Namibia','nex-forms'),
													'NR' => __('Nauru','nex-forms'),
													'NP' => __('Nepal','nex-forms'),
													'AN' => __('Netherlands Antilles','nex-forms'),
													'NL' => __('Netherlands','nex-forms'),
													'NC' => __('New Caledonia','nex-forms'),
													'NZ' => __('New Zealand','nex-forms'),
													'NI' => __('Nicaragua','nex-forms'),
													'NE' => __('Niger','nex-forms'),
													'NG' => __('Nigeria','nex-forms'),
													'NU' => __('Niue','nex-forms'),
													'NF' => __('Norfolk Island','nex-forms'),
													'MP' => __('Northern Mariana Islands','nex-forms'),
													'NO' => __('Norway','nex-forms'),
													'OM' => __('Oman','nex-forms'),
													'PK' => __('Pakistan','nex-forms'),
													'PW' => __('Palau','nex-forms'),
													'PS' => __('Palestinian Territory','nex-forms'),
													'PA' => __('Panama','nex-forms'),
													'PG' => __('Papua New Guinea','nex-forms'),
													'PY' => __('Paraguay','nex-forms'),
													'PE' => __('Peru','nex-forms'),
													'PH' => __('Philippines','nex-forms'),

													'PN' => __('Pitcairn Islands','nex-forms'),
													'PL' => __('Poland','nex-forms'),
													'PT' => __('Portugal','nex-forms'),
													'PR' => __('Puerto Rico','nex-forms'),
													'QA' => __('Qatar','nex-forms'),


													'RE' => __('Reunion','nex-forms'),
													'RO' => __('Romania','nex-forms'),
													'RU' => __('Russia','nex-forms'),
													'RW' => __('Rwanda','nex-forms'),
													'BL' => __('Saint Barthelemy','nex-forms'),
													'SH' => __('Saint Helena','nex-forms'),
													'KN' => __('Saint Kitts and Nevis','nex-forms'),
													'LC' => __('Saint Lucia','nex-forms'),
													'MF' => __('Saint Martin','nex-forms'),
													'PM' => __('Saint Pierre and Miquelon','nex-forms'),
													'VC' => __('Saint Vincent and the Grenadines','nex-forms'),
													'WS' => __('Samoa','nex-forms'),
													'SM' => __('San Marino','nex-forms'),
													'ST' => __('Sao Tome and Principe','nex-forms'),
													'SA' => __('Saudi Arabia','nex-forms'),
													'SN' => __('Senegal','nex-forms'),
													'RS' => __('Serbia','nex-forms'),
													'SC' => __('Seychelles','nex-forms'),
													'SL' => __('Sierra Leone','nex-forms'),
													'SG' => __('Singapore','nex-forms'),
													'SS' => __('SS','nex-forms'),
													'SK' => __('Slovakia (Slovak Republic)','nex-forms'),
													'SI' => __('Slovenia','nex-forms'),
													'SB' => __('Solomon Islands','nex-forms'),
													'SO' => __('Somalia, Somali Republic','nex-forms'),
													'ZA' => __('South Africa','nex-forms'),
													'GS' => __('South Georgia and the South Sandwich Islands','nex-forms'),
													'ES' => __('Spain','nex-forms'),
													'LK' => __('Sri Lanka','nex-forms'),
													'SD' => __('Sudan','nex-forms'),
													'SR' => __('Suriname','nex-forms'),
													'SJ' => __('SJ','nex-forms'),
													'SZ' => __('Swaziland','nex-forms'),
													'SE' => __('Sweden','nex-forms'),
													'CH' => __('Switzerland, Swiss Confederation','nex-forms'),
													'SY' => __('Syrian Arab Republic','nex-forms'),
													'TW' => __('Taiwan','nex-forms'),
													'TJ' => __('Tajikistan','nex-forms'),
													'TZ' => __('Tanzania','nex-forms'),
													'TH' => __('Thailand','nex-forms'),
													'TL' => __('Timor-Leste','nex-forms'),
													'TG' => __('Togo','nex-forms'),
													'TK' => __('Tokelau','nex-forms'),
													'TO' => __('Tonga','nex-forms'),
													'TT' => __('Trinidad and Tobago','nex-forms'),
													'TN' => __('Tunisia','nex-forms'),
													'TR' => __('Turkey','nex-forms'),
													'TM' => __('Turkmenistan','nex-forms'),
													'TC' => __('Turks and Caicos Islands','nex-forms'),
													'TV' => __('Tuvalu','nex-forms'),
													'UG' => __('Uganda','nex-forms'),
													'UA' => __('Ukraine','nex-forms'),
													'AE' => __('United Arab Emirates','nex-forms'),
													'GB' => __('United Kingdom','nex-forms'),
													'US' => __('United States','nex-forms'),
													'UM' => __('United States Minor Outlying Islands','nex-forms'),
													'VI' => __('United States Virgin Islands','nex-forms'),
													'UY' => __('Uruguay','nex-forms'),
													'UZ' => __('Uzbekistan','nex-forms'),
													'VU' => __('Vanuatu','nex-forms'),
													'VE' => __('Venezuela','nex-forms'),
													'VN' => __('Vietnam','nex-forms'),
													'WF' => __('Wallis and Futuna','nex-forms'),
													'EH' => __('Western Sahara','nex-forms'),
													'YE' => __('Yemen','nex-forms'),
													'ZM' => __('Zambia','nex-forms'),
													'ZW' => __('Zimbabwe','nex-forms')
												);
					$total_form_entries 		= 0;
					$total_form_views	 		= 0;
					$total_form_interactions 	= 0;
					$set_form_views 			= 0;
					$set_form_interactions 		= 0;
					$set_form_entries 			= 0;
					
					$days_in_month = '';
					if($month_selected && $month_selected!='0')
						{
						if(function_exists('cal_days_in_month')){
							$days_in_month = cal_days_in_month(CAL_GREGORIAN, (int)$month_selected, $current_year);
							}
						else
							$days_in_month = 31;
						}
					if($chart_view=='summary')
						$days_in_month = $today;
					for($m=1;$m<=12;$m++)
						{
						$submit_array[$m]		= 0;
						$view_array[$m]			= 0;
						$interaction_array[$m]	= 0;
						}
					for($d=1;$d<=$days_in_month;$d++)
						{
						$submit_array_pm[$d] 		= 0;
						$view_array_pm[$d]			= 0;
						$interaction_array_pm[$d]	= 0;
						}
					
					$array_countries = array();
					foreach($country_array as $key=>$val)
						$array_countries[$key] = 0;
						
					foreach($form_entries as $form_entry)
						{
						
						$year = substr($form_entry->date_time,0,4);
						$month = (int)substr($form_entry->date_time,5,2);
						$day = (int)substr($form_entry->date_time,8,2);
						
						if($current_year==$year)
							{
							if($month_selected && $month_selected!='0')
								{
								if($month==$month_selected)
									{
									
									$total_form_entries++;
									
									if($form_entry->country!='')
										$array_countries[$form_entry->country]++;
										
									
									
									for($d=1;$d<=$days_in_month;$d++)
										{
										if($day==$d)
											{
											$submit_array_pm[$d]++;
											}
										}	
									}
								}
							else
								{	
								for($m=1;$m<=12;$m++)
									{
									if($month==$m)
										{
										$submit_array[$m]++;	
										$total_form_entries++;
										if($form_entry->country!='')
											$array_countries[$form_entry->country]++;
											
										
										}
									}
								}
							}
						}	
					foreach($form_views as $view)
						{
						$date = date('Y-m-d h:i:s',$view->time_viewed);
						$year = substr($date,0,4);
						$month = (int)substr($date,5,2);
						$day = (int)substr($date,8,2);
						
						if($current_year==$year)
							{
							if($month_selected && $month_selected!='0')
								{
								if($month==$month_selected)
									{
									$total_form_views++;
									for($dv=1;$dv<=$days_in_month;$dv++)
										{
										if($day==$dv)
											$view_array_pm[$dv]++;		
										}	
									}
								}
							else
								{	
								for($mv=1;$mv<=12;$mv++)
									{
									if($month==$mv)
										{
										$view_array[$mv]++;	
										$total_form_views++;
										}
									}
								}	
							}
						}
					
					foreach($form_interactions as $interaction)
						{
						
						$date = date('Y-m-d h:i:s',$interaction->time_interacted);
						$year = substr($date,0,4);
						$month = (int)substr($date,5,2);
						$day = (int)substr($date,8,2);
						
						if($current_year==$year)
							{
							if($month_selected && $month_selected!='0')
								{
								if($month==$month_selected)
									{
									$total_form_interactions++;
									for($dv=1;$dv<=$days_in_month;$dv++)
										{
										if($day==$dv)
											$interaction_array_pm[$dv]++;		
										}	
									}
								}
							else
								{	
								for($mv=1;$mv<=12;$mv++)
									{
									if($month==$mv)
										{
										$interaction_array[$mv]++;	
										$total_form_interactions++;
										}
									}
								}	
							}
						}
					$output = '';
					
					if(!$checkin)
						{
						for($m=1;$m<=12;$m++)
							{
							$submit_array[$m] = 0;
							$interaction_array[$m] = 0;
							$view_array[$m] = 0;
							}
						
						for($dv=1;$dv<=$days_in_month;$dv++)
							{
							$submit_array_pm[$dv] = 0;
							$interaction_array_pm[$dv] = 0;
							$view_array_pm[$dv] = 0;	
							}
						}
					
					
					
					
					
					
					$output.= '<div class="row stats aa_bg_sec">';
						if(!$checkin)
							{
							$total_form_views=0;
							//$output.= '<div class="alert alert-danger" style="width:95%"><strong>'.__('Plugin NOT Registered!</strong> The below <strong>data is randomized</strong>! To view actual data go to Global Settings above and register the plugin.','nex-forms').'</div>';	
							}
							
							
							$output.= '<div class="col-xs-3" ><span class="big_txt">'.(($checkin) ? $total_form_views : $set_form_views).'</span> <label style="cursor:default;color:#90b5f1;">'.__('Form Views','nex-forms').'</label> </div>';
							$output.= '<div class="col-xs-3" ><span class="big_txt">'.(($checkin) ? $total_form_interactions : $set_form_interactions).'</span> <label style="cursor:default;color:#6ca6e5;">'.__('Form Interactions','nex-forms').'</label> </div>';
							$output.= '<div class="col-xs-3" ><span class="big_txt">'.(($checkin) ? $total_form_entries : $set_form_entries).'</span> <label style="cursor:default;color:#1875d0;">'.__('Form Submissions','nex-forms').'</label> </div>';
							
							if($total_form_entries==0 || $total_form_views==0)
								$output.= '<div class="col-xs-3" ><span class="big_txt">0%</span> <label style="cursor:default;">Conversion</label> </div>';
							else
								$output.= '<div class="col-xs-3" ><span class="big_txt">'.round((($total_form_entries/$total_form_views)*100),2).'%</span> <label>Conversion</label> </div>';
								
								
								
							$output.= '</div>';
							
							$get_countries = $nf7_functions->code_to_country('',1);
							$opacity = 0.4;
							
							$entries_bg_color = '#1976D2';
							$entries_brd_color = '#0074A6';	
							
							$interactions_bg_color = '#a6cde8';
							$interactions_brd_color = '#5196E1';	
							
							$views_bg_color = '#E8F0FE';
							$views_brd_color = '#97BCFB';	
				
							$brd_width = 1;
				
							$chart_type = isset($_REQUEST['chart_type']) ? sanitize_text_field($_REQUEST['chart_type']) : '';
							
								if($checkin)
									{	
								$output .= '
    										<script type="text/javascript">
											 google.charts.load(\'current\', {
        \'packages\':[\'geochart\'],
      });
											  google.charts.setOnLoadCallback(drawRegionsMap);
										
											  function drawRegionsMap() {
										
												var data = google.visualization.arrayToDataTable([
												  [\'Country\', \'Submissions\'],
												  ';
												  if($checkin)
												  	{
													foreach($array_countries as $key=>$value)
														{
														if(is_int($value))
															$output .=	  '[\''.$nf7_functions->code_to_country($key).'\', '.$value.'],';
														
														}
													}
												else
													{
													foreach($get_countries as $key=>$val)
														$output .=	  '["'.str_replace('"','',$val).'", '.rand(0,150).'],';	
													}
												  $output .= '
												]);
										
												var options = {
													colorAxis: {
													  colors: [\'#d6e6fb\', \'#0074a6\'] // Light to dark (custom gradient)
													},
													backgroundColor: \'#ffffff\',
													datalessRegionColor: \'#fff\', // Color for regions without data
													defaultColor: \'#cccccc\'        // Default country color (if not specified)
												  };
										
												var gchart = new google.visualization.GeoChart(document.getElementById(\'regions_div\'));
										
												gchart.draw(data, options);
											  }
											</script>';
									}
							
							
							/*$output .= '
    										<script type="text/javascript">
											  google.charts.load(\'current\', {\'packages\':[\'corechart\']});
      
											  google.charts.setOnLoadCallback(drawChart);
										
											  function drawChart() {
													var data = google.visualization.arrayToDataTable([
													  [\'Views\', \'Interactions\', \'Submissions\'],
													  [\'2004\',  1000,      2355],
													  [\'2005\',  1170,      460],
													  [\'2006\',  660,       1120],
													  [\'2007\',  1030,      540]
													]);
											
													var options = {
													  title: \'Company Performance\',
													  curveType: \'function\',
													  legend: { position: \'bottom\' }
													};
											
													var chart = new google.visualization.LineChart(document.getElementById(\'curve_chart\'));
											
													chart.draw(data, options);
												  }
											</script>';*/
							
									
								
							if($chart_type=='bar')
								{
								$opacity = 1;
								
								$entries_bg_color = '#0074A6';
								$entries_brd_color = '#0074A6';	
								
								$interactions_bg_color = '#5196E1';
								$interactions_brd_color = '#5196E1';	
								
								$views_bg_color = '#97BCFB';
								$views_brd_color = '#97BCFB';
								$brd_width = 0;
								}
								
							if($chart_type=='doughnut' || $chart_type=='polarArea')
								{
								$opacity = 0.9;
								$output .= '<script>
									//randomScalingFactor = function(){ return Math.round(Math.random()*100)};
									
									var lineChartData = {
											labels: [
												"'.__('Views','nex-forms').'",
												"'.__('Interactions','nex-forms').'",
												"'.__('Submissions','nex-forms').'"
											],
									datasets: [
										{
											data: ['.(($checkin) ? $total_form_views : $set_form_views).', '.(($checkin) ? $total_form_interactions : $set_form_interactions).', '.(($checkin) ? $total_form_entries : $set_form_entries).'],
											backgroundColor: [
												"'.NEXForms5_hex2RGB('#e8f0fe',true,',',$opacity).'",
												"'.NEXForms5_hex2RGB('#a6cde8',true,',',$opacity).'",
												"'.NEXForms5_hex2RGB('#1976D2',true,',',$opacity).'"
											],
											hoverBackgroundColor: [
												"#e8f0fe",
												"#a6cde8",
												"#1976D2"
											],
											borderColor : [
												"#fff",
												"#fff",
												"#fff"
											],
											
										}]
									}
								</script>';
								}
							else
								{
								$echo ='';
								if($month_selected && $month_selected!='0')
											{
											for($d=0;$d<=$days_in_month;$d++)
												{
												$echo .= '"'.$d.'"';
												if($d<$days_in_month)
													$echo  .= ',';
												}
											}
										else
											{
											foreach($month_array as $month)
												{
												$echo  .= '"'.$month.'"';
												if($stop_count<12)
													$echo  .= ',';
												$stop_count++;		
												}
											}	
											
								$output.= '<script>
									//randomScalingFactor = function(){ return Math.round(Math.random()*100)};
									lineChartData = {
										labels : [';
										$stop_count = 1;
										if($month_selected && $month_selected!='0')
											{
											for($d=$days_back;$d<=$days_in_month;$d++)
												{
												$output.= '"'.$d.'"';
												if($d<$days_in_month)
													$output.= ',';
												}
											}
										else
											{
											foreach($month_array as $month)
												{
												$output.= '"'.$month.'"';
												if($stop_count<12)
													$output.= ',';
												$stop_count++;		
												}
											}
											
										
											
											
											
											
											
											
											
										$output.= '],
										datasets : [
											{
												label: "'.__('Form Submissions','nex-forms').'",
												backgroundColor : "'.NEXForms5_hex2RGB($entries_bg_color,true,',',$opacity).'",
												borderColor : "'.$entries_brd_color.'",
												borderWidth : '.$brd_width.',
												pointBackgroundColor : "'.$entries_bg_color.'",
												pointHoverBorderWidth : 8,
												fill:true,
												data : [
												';
												if($month_selected && $month_selected!='0')
													{
													$counter = 1;
													foreach($submit_array_pm as $submissions)
														{
														if($counter>=$days_back)
															{
															$output.= $submissions;
															if($counter<$days_in_month)
																$output.= ',';
															}
															$counter++;		
															
														}
													}
												else
													{
													$counter = 1;
													foreach($submit_array as $submissions)
														{
														$output.= $submissions;
														if($counter<12)
															$output.= ',';
														$counter++;		
														}
													}
											$output.= '
													]
											},
											{
												label: "'.__('Form Interactions','nex-forms').'",
												backgroundColor : "'.NEXForms5_hex2RGB($interactions_bg_color,true,',',$opacity).'",
												borderColor : "'.$interactions_brd_color.'",
												borderWidth : '.$brd_width.',
												pointBackgroundColor : "'.$interactions_bg_color.'",
												pointHoverBorderWidth : 8,
												fill:true,
												data : [
												';
												if($month_selected && $month_selected!='0')
													{
													$counter3 = 1;
													foreach($interaction_array_pm as $interaction)
														{
														if($counter3>=$days_back)
															{
															$output.= $interaction;
															if($counter3<$days_in_month)
																$output.= ',';
															}
															$counter3++;	
															
														}
													}
												else
													{
													$counter3 = 1;
													foreach($interaction_array as $interaction)
														{
														$output.= $interaction;
														if($counter3<12)
															$output.= ',';
														$counter3++;				
														}
													}
											$output.= '
													]
											},
											{
												label: "'.__('Form Views','nex-forms').'",
												backgroundColor : "'.NEXForms5_hex2RGB($views_bg_color,true,',',$opacity).'",
												borderColor : "'.$views_brd_color.'",
												borderWidth : '.$brd_width.',
												pointBackgroundColor : "'.$views_bg_color.'",
												pointHoverBorderWidth : 8,
												fill:true,
												data : [
												';
												if($month_selected && $month_selected!='0')
													{
													$counter2 = 1;
													foreach($view_array_pm as $views)
														{
														if($counter2>=$days_back)
															{	
															$output.= $views;
															if($counter2<$days_in_month)
																$output.= ',';
															}
															$counter2++;		
														}
													}
												else
													{
													$counter2 = 1;
													foreach($view_array as $views)
														{
														$output.= $views;
														if($counter2<12)
															$output.= ',';
														$counter2++;				
														}
													}
											$output.= '
													]
											},
											
											
											
										]
									}
								  </script>
								  ';
								}
						$ajax = isset($_REQUEST['ajax']) ? sanitize_text_field($_REQUEST['ajax']) : '';
						
						
						
						arsort($array_countries);

						// Get the top 10 entries
						$top10 = array_slice($array_countries, 0, 10, true);
						
						$set_total = (int) 0;
						foreach ($top10 as $countryCode => $count) {
							$set_total += $count;
						}
						
						// Output the top 10
						$output .= '<div class="top_countries">';
						foreach ($top10 as $countryCode => $count) {
							if($count!=0)
								{
								$output .= '<div class="p_holder">';
								$output .= '<div class="progress-label">'.$nf7_functions->code_to_country_flag($countryCode).' '.$nf7_functions->code_to_country($countryCode).'</div>';
								$output .= '<div class="total_votes">'.$count.'</div>';
									$output .= '<div class="progress">';
										$output .= '<div class="progress-bar nf-vote loading-vote" role="progressbar" style="width: '.(($count/$set_total)*100).'%; background:#1a73e8;" data-value="'.(($count/$set_total)*100).'" aria-valuenow="'.(($count/$set_total)*100).'" aria-valuemin="0" aria-valuemax="100">';
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
								}
						}
						$output .= '</div>';
						
						
						
						//GET BEST FORMS
						//SELECT nex_forms_Id, COUNT(*) AS total_entries FROM wp_wap_nex_forms_entries WHERE YEAR(date_time) = 2025 GROUP BY nex_forms_Id ORDER BY total_entries DESC LIMIT 0, 10;
						if($month_selected && $month_selected!='0')
							$add_month = $wpdb->prepare('AND MONTH(date_time) = %d',$month_selected);
						
						$top_forms = $wpdb->get_results($wpdb->prepare('SELECT nex_forms_Id, COUNT(*) AS total_entries FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE YEAR(date_time) = %d '.$add_month.' GROUP BY nex_forms_Id ORDER BY total_entries DESC LIMIT 0, 5', sanitize_text_field($current_year))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						
						$output .= '<div class="top_forms">';
						foreach($top_forms as $top_form)
							{
							$output .= '<div class="top_form_item">';
								$output .= '<div class="top_form_title">'.NEXForms_get_title($top_form->nex_forms_Id,'wap_nex_forms').'</div>';
								$output .= '<div class="top_form_total">'.$top_form->total_entries.'</div>';	
							$output .= '</div>';
							}
						$output .= '</div>';
						
						
						if($ajax)
							{
							NEXForms_clean_echo( $output);
							die();
							}
						else
							return $output;
		}
		
		
		public function print_record_table(){
			
			global $wpdb;
			
			$functions = new NEXForms_functions();
			$database_actions = new NEXForms_Database_Actions();
			
			$output = '';
			
			$show_delete = (isset($_POST['show_delete'])) ? sanitize_text_field($_POST['show_delete']) : $this->show_delete;
			
			$output .= '<div class="dashboard-box database_table '.$this->table.' '.$this->extra_classes.'" data-table="'.$this->table.'">';
				$output .= '<div class="dashboard-box-header '.(($this->color_adapt) ? 'aa_bg_main': '' ).'">';
					$output .= '<div class="table_title '.(($this->color_adapt) ? 'font_color_1': '' ).' ">';
					
					$output .= $this->table_header;
					
					//if($this->action_button)
					//	$output .= '<a class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">'.$this->action_button.'</i></a>';
					//else
					//	$output .= '<i class="material-icons header-icon">'.$this->table_header_icon.'</i><span class="header_text '.(($this->action_button) ? 'has_action_button' : '' ).'">'.$this->table_header.'</span>';
					//<span class="header_text '.(($this->action_button) ? 'has_action_button' : '' ).'">'.$this->table_header.'</span>
					$output .= '</div>';
					if($this->show_search)
						{
						$output .= '  <div class="search_box">
							<div class="input-field">
							<input id="search" type="text" class="search_box aa_bg_main_input material-d" value="" placeholder="'.__('Search...','nex-forms').'" name="table_search_term">
							<i class="fa fa-search do_search font_color_1"></i>
							<i class="fa fa-close do_search font_color_1"></i>
						   </div>
						   </div>
						';
						}
					if(is_array($this->extra_buttons))
						{
						$output .= '<div class="dashboard-box-header-buttons">';
						foreach($this->extra_buttons as $button)
							{
							$adapt_color = 'aa_bg_main_btn';
							
							$button_rank = isset($button['rank']) ? $button['rank'] : '';
							
							$button_rank=='2';
								$adapt_color = 'aa_bg_sec_btn';
								
							if($button['type']=='link')
								$output .= '<a href="'.$button['link'].'" class="'.$button['class'].' nf_button '.$adapt_color.'" id="'.$button['id'].'">'.$button['icon'].'</a>';
							else
								$output .= '<a href="#" class="'.$adapt_color.' '.$button['class'].' nf_button" id="'.$button['id'].'">'.$button['icon'].'</a>';
							}
						$output .= '</div>';
						}
					
				if($this->build_table_dropdown)
					{
					$output .= '<select class="form-control table_dropdown" name="'.$this->build_table_dropdown.'">';
						$output .= '<option value="0" selected>'.__('--- Select Form ---','nex-forms').'</option>';
						$get_forms = 'SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE is_template<>1 AND is_form<>"preview" AND is_form<>"draft" ORDER BY Id DESC';
						$forms = $wpdb->get_results($get_forms); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						foreach($forms as $form)
							$output .= '<option value="'.$form->Id.'">'.$database_actions->get_total_records($this->table,'',$form->Id).' - '.$form->title.'</option>';
					$output .= '</select>';
					}
				$output .= '</div>';
				$output .= '<div  class="dashboard-box-content zero_padding">';
				
					$output .= '<table class="'.(($this->table_resize) ? 'fixed_headers' : '').'">'; //highlight
					if($this->show_headings)
						{
						$output .= '<thead>';
							$output .= '<tr>';
							$output .= '<th class="batch-actions">
							<input id="rs-check-all" name="check-all" value="check-all" type="checkbox">
							</th>';
							foreach($this->table_headings as $key=>$val)
								{
								if(is_array($val))
									{
									if(array_key_exists('heading',$val))
										$output .= '<th class="db-table-head '.((isset($val['set_class'])) ? $val['set_class'] : '').' '.$functions->format_name($val['heading']).' '.((isset($val['sort_by']) && $this->sortable_columns) ? 'sortable' : '' ).'" '.((isset($val['sort_by'])) ? 'data-sort-by="'.$val['sort_by'].'"' : '' ).'>'.$functions->unformat_records_name($val['heading']).'</th>';
									if(array_key_exists('icon',$val))
										$output .= '<th class="db-table-head '.((isset($val['set_class'])) ? $val['set_class'] : '').' '.((isset($val['sort_by']) && $this->sortable_columns) ? 'sortable' : '' ).'" '.(($val['sort_by']) ? 'data-sort-by="'.((isset($val['sort_by'])) ? $val['sort_by'] : '').'"' : '' ).'><span class="'.$val['icon'].'"></span></th>';
									}
								else
									$output .= '<th class="db-table-head  '.(($this->sortable_columns) ? 'sortable' : '' ).' '.$functions->format_name($val).'" data-sort-by="'.$functions->format_name($val).'">'.$functions->unformat_records_name($val).'</th>';
								}
							if($show_delete)
								$output .= '<th class="db-table-head  delete"></th>';	
							$output .= '</tr>';
						    
								
						$output .= '</thead>';
						}
						//$output .= $functions->print_preloader('big','blue',false,'database-table-loader');
						$output .= '<tbody class="'.(($this->checkout) ? 'saved_records_container' : 'saved_records_contianer').'">'.$this->get_table_records($this->additional_params, $this->search_params, $this->table_headings, $this->is_report ).'</tbody>';

					$output .= '</table>';
				$output .= '</div>';
				$output .= '<div class="paging_wrapper">';
				
			
				
					$output .='<input type="hidden" value="0" name="current_page" />';
					
					$output .="<input type='hidden' value='".json_encode($this->additional_params,JSON_UNESCAPED_UNICODE)."' name='additional_params' />";
					$output .="<input type='hidden' value='".json_encode($this->field_selection,JSON_UNESCAPED_UNICODE)."' 	name='field_selection' />";
					$output .="<input type='hidden' value='".json_encode($this->search_params,JSON_UNESCAPED_UNICODE)."'     name='search_params' />";
					$output .="<input type='hidden' value='".json_encode($this->table_headings,JSON_UNESCAPED_UNICODE)."'    name='header_params' />";
					$output .="<input type='hidden' value='' name='sort_by' />";
					$output .="<input type='hidden' value='DESC' name='sort_by_direction' />";
					$output .="<input type='hidden' value='".$this->is_report."'    	name='is_report' />";
					$output .="<input type='hidden' value='".$this->table."'     		name='database_table' />";
					$output .="<input type='hidden' value='".$this->record_limit."'     name='record_limit' />";
					$output .="<input type='hidden' value='".$this->show_delete."'     name='show_delete' />";
					$output .="<input type='hidden' value='".$this->action."'     name='do_action' />";
					
					
					$total_record = $database_actions->get_total_records($this->table,$this->additional_params,'', $this->search_params,'');
					
						$output .= '<div class="paging">';
						
						if($this->show_paging)
							{
							$output .= '
							<span class="displaying-num"><span class="entry-count">'.$total_record.'</span> '.__('items ','nex-forms').'</span>
							<span class="pagination-links">
								
								<span class="paging-input">Page <span class="current-page">1</span> '.__('of','nex-forms').' <span class="total-pages">'.(($total_record>$this->record_limit) ? round(($total_record/$this->record_limit)+1,0) : '1').'</span><span class="records_per_page">
								<select name="set_record_per_page">
								<option value="10" '.(($this->record_limit==10) ? 'selected="selected"' : '').'>10</option>
								<option value="20" '.(($this->record_limit==20) ? 'selected="selected"' : '').'>20</option>
								<option value="50" '.(($this->record_limit==50) ? 'selected="selected"' : '').'>50</option>
								<option value="100" '.(($this->record_limit==100) ? 'selected="selected"' : '').'>100</option>
								<option value="150" '.(($this->record_limit==150) ? 'selected="selected"' : '').'>150</option>
								<option value="300" '.(($this->record_limit==300) ? 'selected="selected"' : '').'>300</option>
								<option value="500" '.(($this->record_limit==500) ? 'selected="selected"' : '').'>500</option>
								<option value="1000" '.(($this->record_limit==1000) ? 'selected="selected"' : '').'>1000</option>
								</select> '.__('records p/page','nex-forms').'</span>
							
								<a title="'.__('Go to the first page','nex-forms').'" class="first-page iz-first-page btn waves-effect waves-light"><span class="fa fa-backward-step"></span></a>
								<a title="'.__('Go to the next page','nex-forms').'" class="iz-prev-page btn waves-effect waves-light prev-page"><span class="fa fa-angle-left"></span></a>
								
								<a title="'.__('Go to the next page','nex-forms').'" class="iz-next-page btn waves-effect waves-light next-page"><span class="fa fa-angle-right"></span></a>
								<a title="'.__('Go to the last page','nex-forms').'" class="iz-last-page btn waves-effect waves-light last-page"><span class="fa fa-forward-step"></span></a>
							</span>
							
							';	
							}	
						if($this->show_more_link){
							$output .= '<a href="'.$this->show_more_link['link'].'" class="show_more_button">'.$this->show_more_link['text'].' <span class="fa fa-arrow-right"></span></a>';
						}
						$output .= '</div>';
						
				$output .= '</div>';
				
			$output .= '</div>';
			
			return $output;
		}	
		public function get_table_records($additional_params=array(), $search_params=array(), $header_params=array(), $is_report=false){
			
			$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';
			$set_is_report = (isset($_POST['is_report'])) ? sanitize_text_field($_POST['is_report']) : $is_report;

			if($do_ajax)
				{
				if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) )
					wp_die();
				}
			
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			
			$output = '';
			$page_num = isset($_POST['page']) ? esc_sql(sanitize_title($_POST['page'])) : 0;
			$page_num = sanitize_title($page_num);
			
			$page_num = isset($_POST['page']) ? esc_sql(sanitize_title($_POST['page'])) : 0;
			$page_num = sanitize_title($page_num);
			$search_term = isset($_POST['search_term']) ?  esc_sql(sanitize_title($_POST['search_term'])) : '';
			$limit = 10;			
			
			$nf_functions = new NEXForms_Functions();
			$database_actions = new NEXForms_Database_Actions();
			
			$header_params = (isset($_POST['header_params'])) ? sanitize_text_field($_POST['header_params']) : '';
			$additional_params = (isset($_POST['additional_params'])) ?  sanitize_text_field($_POST['additional_params']) : '';
			$field_selection = (isset($_POST['field_selection'])) ? esc_sql(sanitize_text_field($_POST['field_selection'])) : '';
			$search_params = (isset($_POST['search_params'])) ?  esc_sql(sanitize_text_field($_POST['search_params'])) : '';
			
			
			
			$do_action = (isset($_POST['do_action'])) ? esc_sql(sanitize_title($_POST['do_action'])) : $this->action;
			
			$sort_by_table = '';
			
			if($do_action=='print_entries')
				{
				if($_POST['sort_by']=='title')
					$sort_by_table = $wpdb->prefix.'wap_nex_forms.';
				else
					$sort_by_table = $wpdb->prefix.'wap_nex_forms_entries.';
				}
			
			$sort_by = (isset($_POST['sort_by']) && $_POST['sort_by']!='') ? $wpdb->prepare('%s',esc_sql(sanitize_title($_POST['sort_by']))) : 'Id';
			$sort_by = $sort_by_table.$sort_by;
			$sort_by = str_replace('\'','',$sort_by);
			$sort_by_direction =(isset($_POST['sort_by_direction']) && $_POST['sort_by_direction']!='') ? $wpdb->prepare('%s',esc_sql(sanitize_title($_POST['sort_by_direction']))) : 'DESC';
			$sort_by_direction = str_replace('\'','',$sort_by_direction);
			$record_limit = (isset($_POST['record_limit'])) ? $wpdb->prepare('%d',esc_sql(sanitize_title($_POST['record_limit']))) : $wpdb->prepare('%d',esc_sql($this->record_limit));
	
			
			if($header_params)
				{
				$set_header_params = isset($header_params) ? $header_params : '';
				if(!is_array($set_header_params))
					$header_params = json_decode(str_replace('\\','',$set_header_params),true);
				else
					$header_params = $wpdb->prepare('%s',esc_sql($set_header_params));
				}
			else
				$header_params = $this->table_headings;
				
			if($additional_params)
				{
				$set_params = isset($additional_params) ? $additional_params : '';
				if(!is_array($set_params))
					$additional_params = json_decode(str_replace('\\','',$set_params),true);
				else
					$additional_params = $wpdb->prepare('%s',esc_sql($set_params));
				}
			else
				$additional_params = $this->additional_params;
				
			if($field_selection)
				{
				$set_field_selection = isset($field_selection) ? $field_selection : '';
				if(!is_array($set_field_selection))
					$field_selection = json_decode(str_replace('\\','',$set_field_selection),true);
				else
					$field_selection = $wpdb->prepare('%s',esc_sql($set_field_selection));
				}
			else
				$field_selection = $this->field_selection;	
			
			
			
			if($search_params)
				{
				$set_search_params = isset($search_params) ? $search_params : '';
				if(!is_array($set_search_params))
					$search_params = json_decode(str_replace('\\','',$set_search_params),true);
				else
					$search_params = $wpdb->prepare('%s',esc_sql($set_search_params));
				}
			else
				$search_params = $this->search_params;
			
			if(isset($_POST['table']))
				$table = $wpdb->prepare('%s',esc_sql($_POST['table']));
			else
				$table = $wpdb->prepare('%s',$this->table);
			
			$table = str_replace('\'','',$table);
				
			$where_str = '';
			$show_hide_field = (isset($_POST['showhide_fields'])) ? str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_text_field($_POST['showhide_fields'])))) : '';
			
			$show_cols = esc_sql(sanitize_text_field($show_hide_field));
			
			if(is_array($additional_params))
				{
				foreach($additional_params as $clause)
					{
					$like = '';
					if($clause['operator'] == 'LIKE' || $clause['operator'] == 'NOT LIKE')
						$like = '%';
					if($clause['value']=='NULL')
						$where_str .= ' AND `'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($clause['column']))).'` '.(($clause['operator']!='') ? str_replace('\'','',$wpdb->prepare('%s',$clause['operator'])) : '=').'  '.str_replace('\'','',$wpdb->prepare('%s',$like.esc_sql(sanitize_text_field($clause['value'])).$like));
					else
						$where_str .= ' AND `'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($clause['column']))).'` '.(($clause['operator']!='') ? str_replace('\'','',$wpdb->prepare('%s',$clause['operator'])) : '=').'  "'.$like.str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_text_field($clause['value'])))).$like.'"';
					
					}
				}
			
			$select_fields = '*';
			if($is_report)
				{
				$set_field_selection = array();
				$table_fields = $wpdb->get_results('SHOW FIELDS FROM '.$wpdb->prefix.$table); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$db_cols = array();
				foreach($table_fields as $col)
					{
						if($col->Field!='Id')
							{
							if(in_array($col->Field,$field_selection))
								$set_field_selection[] = $col->Field;
							}
					}
				$diff = array_diff($field_selection,$set_field_selection);
				$field_selection = $set_field_selection;
				/*if(!empty($diff))
					{
					foreach($diff as $no)
						{
						$unconverted_fields .= ' '.$no.',';
						}
					echo '<div class="alert alert-info">The following fields could not be converted into MySQL table columns: '.$unconverted_fields.'</div>';
					//	print_r($diff);
					//echo '</pre>';
					}*/
				}
			
			if(is_array($field_selection))
				{
				$j=1;
				$select_fields = '';
				foreach($field_selection as $field_select)
					{
					
					if($j<count($field_selection))
						 $select_fields .= '`'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($field_select))).'`,';
					else
						$select_fields .= '`'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($field_select))).'`';
					$j++;
					}
				}
				
			$count_search_params = 0;
			
			if(is_array($search_params))
				$count_search_params = count($search_params);
				
			if(is_array($search_params) && $search_term)
				{
				if($count_search_params>1)
					{
					$where_str .= ' AND (';
					$loop_count = 1;
					foreach($search_params as $column)
						{
						if($loop_count==1)
							$where_str .= '`'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($column))).'` LIKE "%'.str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_title($search_term)))).'%" ';
						else
							$where_str .= ' OR `'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($column))).'` LIKE "%'.str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_title($search_term)))).'%" ';
							
						$loop_count++;
						}
					$where_str .= ') ';
					}
				else
					{
					foreach($search_params as $column)
						{
						$where_str .= ' AND `'.str_replace('\'','',$wpdb->prepare('%s',esc_sql($column))).'` LIKE "%'.str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_title($search_term)))).'%" ';
						}
					}
				}
			
			$entry_report_id = (isset($_POST['entry_report_id'])) ? str_replace('\'','',$wpdb->prepare('%d',esc_sql(sanitize_title($_POST['entry_report_id'])))) : '';
			$form_id = (isset($_POST['form_id'])) ? str_replace('\'','',$wpdb->prepare('%d',esc_sql(sanitize_title($_POST['form_id'])))) : '';
			$post_table = (isset($_POST['table'])) ? str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_title($_POST['table'])))) : '';
			
			$is_report = (isset($_POST['is_report'])) ? str_replace('\'','',$wpdb->prepare('%s',esc_sql(sanitize_text_field($_POST['is_report'])))) : $this->is_report;
			
			if($entry_report_id)
				{
				$where_str .= ' AND nex_forms_Id = '.$wpdb->prepare('%d',esc_sql(sanitize_title($entry_report_id)));
				$nex_forms_id = esc_sql(sanitize_title($entry_report_id));
				}
			if($form_id)
				{
				$where_str .= ' AND nex_forms_Id = '.$wpdb->prepare('%d',esc_sql(sanitize_title($form_id)));
				$nex_forms_id = $wpdb->prepare('%d',esc_sql(sanitize_title($form_id)));
				}
			
			if($post_table)
				$output = '<div class="total_table_records hidden">'.$database_actions->get_total_records($table,$additional_params,$nex_forms_id, $search_params,$search_term).'</div>';
		
			
			if($do_action=='print_entries')
				$get_records = 'SELECT '.$select_fields.', title FROM '.$wpdb->prefix.$table.', '.$wpdb->prefix.'wap_nex_forms WHERE '.$wpdb->prefix.$table.'.Id<>"" AND '.$wpdb->prefix.'wap_nex_forms.Id = '.$wpdb->prefix.$table.'.nex_forms_Id '.$where_str.' ORDER BY '.$sort_by.' '.$sort_by_direction.' LIMIT '.($page_num*$record_limit).','.$record_limit;
			else
				$get_records = 'SELECT '.$select_fields.' FROM '.$wpdb->prefix.$table.'  WHERE Id<>"" '.$where_str.' ORDER BY '.$sort_by.' '.$sort_by_direction.' LIMIT '.($page_num*$record_limit).','.$record_limit;
			
			$records = $wpdb->get_results($get_records); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			$get_temp_table_details = get_option('tmp_csv_export');
			update_option('tmp_csv_export',array('query'=>$get_records,'cols'=>$field_selection,'form_Id'=>$get_temp_table_details['form_Id']));
			
			$img_ext_array = array('jpg','jpeg','png','tiff','gif','psd');
			$file_ext_array = array('doc','docx','mpg','mpeg','mp3','mp4','odt','odp','ods','pdf','ppt','pptx','txt','xls','xlsx');
				foreach($records as $record)
					{
					$record_val = '';
					$output .= '<tr class="form_record" id="'.$record->Id.'">';
						$output .= '<td class="batch-actions"><input id="rs-check-all-'.$record->Id.'" name="record[]" value="'.$record->Id.'" type="checkbox"></td>';
					if($is_report)
						{
						foreach($record as $record_head=>$record_val)
							{
							if($record_head!='Id')
								{
								
								//$field_value = $data->field_value;
								$set_val = '';
								if(strstr($record_val,'||'))
									{
									
									$get_val = explode('||',$record_val);
									
									foreach($get_val as $setkey=>$setval)
										{
										$set_val .= ''.trim($setval).',';	
										}
									
									$record_val = $set_val;
									
									}
								
								
								if($nf_functions->isJson($record_val) && !is_numeric($record_val))
										{
										$output .= '<td class="'.$val.'" style="">';
										$json = json_decode($record_val,1);
										
										$output .= '<table width="100%" class="highlight inner-data-table" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #ddd; border-left:1px solid #ddd; border-top:1px solid #ddd;">';
										$i = 1;
										foreach($json as $value)
											{
											if(is_array($value) || is_object($value))
												{
													
													if($i==1)
														{
														$output .= '<tr>';
														foreach($value as $innerkey=>$innervalue)
															{
															if(!strstr($innerkey,'real_val__'))	
																$output .= '<td style="border-bottom:1px solid #ddd;border-right:1px solid #ddd;"><strong>'.ucfirst($nf_functions->unformat_records_name($innerkey)).'</strong></td>';
															}
														$output .= '</tr>';
														}
													
													$output .= '<tr>';
													foreach($value as $innerkey=>$innervalue)
														{
														if(array_key_exists('real_val__'.$innerkey,$value))
																{
																$realval = 'real_val__'.$innerkey;
																$innervalue = $val->$realval;	
																
																}
														if(!strstr($innerkey,'real_val__'))
															{
															
															if(in_array($nf_functions->get_ext($innervalue),$img_ext_array))
																$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;"><img class="materialboxed" src="'.rtrim($innervalue,', ').'" width="80px" /></td>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
															else
																$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;">'.rtrim($innervalue,', ').'</td>'; 
															
															}
														}
														
													$output .= '</tr>';
													
												}
											else
												$output .= ''.rtrim(esc_html(strip_tags($value)),', ').'';
											
											$i++;
											}
										
										$output .= '</table>';
										$output .= '</td>';
										}
									else if(strstr($record_val,',') && !strstr($record_val,'data:image'))
										{
										$is_array = explode(',',$record_val);
										$output .= '<td class="image_td '.$val.'">';
										foreach($is_array as $item)
											{
											if(in_array($nf_functions->get_ext($item),$img_ext_array))
												$output .= '<img class="materialboxed"  width="40px" src="'.$item.'">'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
											else if(in_array($nf_functions->get_ext($item),$file_ext_array))
												$output .= '<a class="btn file_upload_link" href="'.$item.'" target="_blank"><i class="fa fa-file"></i> '.$nf_functions->get_ext($item).'</a>';
											else
												$output .= $item;
											}
										$output .= '</td>';
										}
									else if(strstr($record_val,'data:image'))
										$output .= '<td class="'.$val.'"><img  width="100px" src="'.$record_val.'" /></td>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
									else if(in_array($nf_functions->get_ext($record_val),$img_ext_array) && $val!='name')
										$output .= '<td class="'.$val.'"><img class="materialboxed"  width="65px" src="'.esc_html(strip_tags($record_val)).'"></td>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
									else{
										
								
										$output .= '<td class="report_row '.$record_val.'">'.$nf_functions->view_excerpt(esc_html(strip_tags($record_val)),30).'</td>';
									}
								
								
								}
							}
						}
					else
						{
						foreach($header_params as $key=>$val)
							{
								
							
								
							if(is_array($val))
								{
								$func_args_1 = (isset($val['user_func_args_1'])) ? $val['user_func_args_1'] : '';
								$func_args_2 = (isset($val['user_func_args_2'])) ? $val['user_func_args_2'] : '';
								$func_args_3 = (isset($val['user_func_args_3'])) ? $val['user_func_args_3'] : '';
								$func_args_4 = (isset($val['user_func_args_4'])) ? $val['user_func_args_4'] : '';
								$func_args_5 = (isset($val['user_func_args_5'])) ? $val['user_func_args_5'] : '';
								$func_args_6 = (isset($val['user_func_args_6'])) ? $val['user_func_args_6'] : '';
								
								
								$whitelist_func = NEXForms_safe_user_functions();
								if(isset($val['user_func_class']))
									{
									if(in_array($val['user_func'],$whitelist_func))
										$output .= '<td class="'.$nf_functions->format_name($val['heading']).' '.((isset($val['set_class'])) ? $val['set_class'] : '').'">'.call_user_func(array($val['user_func_class'],$val['user_func']), array($record->$func_args_1, $func_args_2)).'</td>';
									}
								else
									{
									if(in_array($val['user_func'],$whitelist_func))
										$output .= '<td class=" '.((isset($val['set_class'])) ? $val['set_class'] : '').'">'.call_user_func($val['user_func'], array($record->$func_args_1, $func_args_2)).'</td>';
									}
								}
							else
								{
								if($val)
									{
							
									if($nf_functions->isJson($record_val) && !is_numeric($record_val))
										{
										$output .= '<td class="'.$val.'" style="overflow-x:auto;overflow-y:auto;">';
										$json = json_decode($record->$val,1);
										
										$output .= '<table width="100%" class="highlight" cellpadding="3" cellspacing="0" style="border-bottom:1px solid #ddd; border-left:1px solid #ddd; border-top:1px solid #ddd;">';
										$i = 1;
										foreach($json as $value)
											{
											if(is_array($value) || is_object($value))
												{
													
													if($i==1)
														{
														$output .= '<tr>';
														foreach($value as $innerkey=>$innervalue)
															{
															if(!strstr($innerkey,'real_val__'))	
																$output .= '<td style="border-bottom:1px solid #ddd;border-right:1px solid #ddd;"><strong>'.$nf_functions->unformat_records_name($innerkey).'</strong></td>';
															}
														$output .= '</tr>';
														}
													
													$output .= '<tr>';
													foreach($value as $innerkey=>$innervalue)
														{
														if(array_key_exists('real_val__'.$innerkey.'',$val))
																{
																$realval = 'real_val__'.$innerkey;
																$innervalue = $val->$realval;	
																
																}
														if(!strstr($innerkey,'real_val__'))
															{
															
															if(in_array($nf_functions->get_ext($innervalue),$img_ext_array))
																$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;"><img class="materialboxed" src="'.rtrim($innervalue,', ').'" width="80px" /></td>';// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
															else
																$output .= '<td style="border-right:1px solid #ddd;border-bottom:1px solid #eee;">'.rtrim($innervalue,', ').'</td>';
															
															}
														}
														
													$output .= '</tr>';
													
													//foreach($value as $innerkey => $innervalue)
														//{
														//$output .= '<strong>'.$nf_functions->unformat_records_name($innerkey).'</strong>: '.$innervalue.' | ';	
														//}
													//$output .= '<br />';	
												}
											else
												$output .= ''.rtrim(esc_html(strip_tags($value)),', ').'';
											
											$i++;
											}
										
										$output .= '</table>';
										$output .= '</td>';
										}
									else if(strstr($record->$val,',') && !strstr($record->$val,'data:image'))
										{
										$is_array = explode(',',$record->$val);
										$output .= '<td class="image_td '.$val.'">';
										foreach($is_array as $item)
											{
											if(in_array($nf_functions->get_ext($item),$img_ext_array))
												$output .= '<img class="materialboxed"  width="65px" src="'.$item.'">'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
											else if(in_array($nf_functions->get_ext($item),$file_ext_array))
												$output .= '<a class="btn file_upload_link" href="'.$item.'" target="_blank"><i class="fa fa-file"></i> '.$nf_functions->get_ext($item).'</a>';
											else
												$output .= $item;
											}
										$output .= '</td>';
										}
									else if(strstr($record->$val,'data:image'))
										$output .= '<td class="'.$val.'"><img  width="100px" src="'.$record->$val.'" /></td>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
									else if(in_array($nf_functions->get_ext($record->$val),$img_ext_array) && $val!='name')
										$output .= '<td class="'.$val.'"><img class="materialboxed"  width="65px" src="'.esc_html(strip_tags($record->$val)).'"></td>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
									else{
										
								
										$output .= '<td class="'.$val.'">'.$nf_functions->view_excerpt(esc_html(strip_tags($record->$val)),30).'</td>';
									}
									}
								else
									$output .= '<td>&nbsp;</td>';
								}
							}
						
						//$theme = wp_get_theme();
						///if($theme->Name=='NEX-Forms Demo' && $record->Id<22)
						//	$output .= '<td class="td_right"></td>';
						//else
						}
						
						$show_delete = (isset($_POST['show_delete'])) ? sanitize_text_field($_POST['show_delete']) : $this->show_delete;
						
						if($show_delete)
							$output .= '<td class="td_right col_delete"><a class="delete"><i id="'.$record->Id.'" data-table="'.$table.'"  data-placement="bottom" class="delete-record fas fa-trash" data-title="'.__('Delete','nex-forms').'" data-toggle="tooltip_bs2" title="'.__('Delete Record','nex-forms').'"></i></a></td>';
					$output .= '</tr>';
					}
			
			if(!$records)
				{
				$output .= '<div class="no_records"><span class="fa fa-ban"></span> <span class="result_text">No results found'.(($search_term) ? ' containing '.$search_term : '').'</span></div>';
					
				}
				
			$do_ajax = (isset($_POST['do_ajax'])) ? sanitize_text_field($_POST['do_ajax']) : '';

			if($do_ajax)
				{
				NEXForms_clean_echo($output);
				wp_die();
				}
			else
				return $output;
				
				
		}
		public function get_total_entries($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$total_entries = $wpdb->get_var('SELECT count(*) FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id='.$set_form_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			return $total_entries;
		}
		
		public function get_total_entries_2($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$total_entries = $wpdb->get_var('SELECT count(*) FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id='.$set_form_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			return ($total_entries>0) ? '<a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions&folder='.$set_form_id.'"><span class="total_entries_display menu_badge">'.$total_entries.'</span></a>' : '<span class="total_entries_display">'.$total_entries.'</span>';
		}
		
		
		public function get_total_entries_3($id){
			global  $wpdb;
			
			if(is_array($id))
				$id = $id[0];
			$set_count = $wpdb->get_var('SELECT count(*) FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id='.$id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			return ($set_count>0) ? '<a href="'.get_admin_url().'admin.php?page=nex-forms-page-submissions&folder='.$id.'" ><span data-title="View Form Entries" title="View Form Entries" data-toggle="tooltip_bs2" data-placement="bottom" class="total_entries_display menu_badge">'.$set_count.'</span></a>' : '<span class="total_entries_display">'.$set_count.'</span>';
		}
		
		
		
		
		public function duplicate_record($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
				
			return '<a id="'.$set_form_id.'" class="duplicate_record" title="'.__('Duplicate Form','nex-forms').'" ><i class="fa fa-files-o" data-title="'.__('Duplicate Form','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"></i></a>';
		}
		
		public function link_form_title($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$title = $wpdb->get_var('SELECT title FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id='.$set_form_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			$title= wp_unslash($title);
			$title= str_replace('\"','',$title);
			$title= str_replace('/','',$title);
			$title = sanitize_text_field( $title );
			
			
			return '<a href="'.get_admin_url().'admin.php?page=nex-forms-builder&open_form='.$set_form_id.'"  class="edit_record" title="'.__('Edit Form','nex-forms').'"><i class="fa fa-edit" data-title="'.__('Edit Form','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"></i></a>';
		}
		
		public function report_last_update($date_time){
			global  $wpdb;
			
			if(is_array($date_time))
				$date_time = $date_time[0];
			
			
			return $date_time;
		}
		public function get_total_report_records($db_table){
			global  $wpdb;
			
			if(is_array($db_table))
						$db_table = $db_table[0];
			if($wpdb->get_var("show tables like '".$db_table."'") == $db_table) // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					{
					
					$total_entries = $wpdb->get_var('SELECT count(*) FROM '.$db_table); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					return '<span class="total_entries_display menu_badge">'.$total_entries.'</span>';
					}
				else
					{
					return '<span class="total_entries_display menu_badge">0</span>';
					}
		}
		public function link_form_title_2($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$title = $wpdb->get_var('SELECT title FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id='.$set_form_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery


			$title= wp_unslash($title);
			$title= str_replace('\"','',$title);
			$title= str_replace('"','',$title);
			$title= str_replace("\'",'',$title);
			$title= str_replace("'",'',$title);
			$title= str_replace('/','',$title);
			$title = sanitize_text_field( $title );
			
			return '<a href="'.get_admin_url().'admin.php?page=nex-forms-builder&open_form='.$set_form_id.'" class="form_title"   title="Edit - '.$title.'" data-title="'.__('Edit Form','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom">'.$title.'</a>';
		}
		
		public function link_report_title($report_Id){
			global  $wpdb;
			
			if(is_array($report_Id))
				$set_report_id = $report_Id[0];
			$title = $wpdb->get_var('SELECT report_title FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id='.$set_report_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			$title= wp_unslash($title);
			$title= str_replace('\"','',$title);
			$title= str_replace('"','',$title);
			$title= str_replace("\'",'',$title);
			$title= str_replace("'",'',$title);
			$title= str_replace('/','',$title);
			$title = sanitize_text_field( $title );
			
			return '<a href="#" class="form_title open_report" id="'.$set_report_id.'"  title="Edit - '.$title.'" data-title="'.__('Edit Report','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom">'.$title.'</a>';
		}
		
		public function link_report_title2($report_Id){
			global  $wpdb;
			
			if(is_array($report_Id))
				$set_report_id = $report_Id[0];
			$title = $wpdb->get_var('SELECT report_title FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id='.$set_report_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			$title= wp_unslash($title);
			$title= str_replace('\"','',$title);
			$title= str_replace('"','',$title);
			$title= str_replace("\'",'',$title);
			$title= str_replace("'",'',$title);
			$title= str_replace('/','',$title);
			$title = sanitize_text_field( $title );
			
			return '<a href="#" class="form_title open_report" id="'.$set_report_id.'"  title="Edit - '.$title.'" data-title="'.__('Edit Report','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"><i class="fa fa-edit"></i></a>';
		}
		
		public function quick_report_csv($report_Id){
			global  $wpdb;
			
			if(is_array($report_Id))
				$set_report_id = $report_Id[0];
			
			
			$report = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id='.$set_report_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			if($report->status=='3')
				{
				return '<a href="'.admin_url().'admin.php?page=nex-forms-dashboard&amp;export_csv=true&amp;&amp;report_Id='.$set_report_id.'" class="form_title open_report" id="'.$set_report_id.'"  title="Edit -" data-title="'.__('Export Report to CSV (Excell)','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"><i class="fa-regular fa-file-excel"></i></a>';
				}
			else
				{
				return '<i class="disabled fa-regular fa-file-excel"></i>';
				}
			
			
		}
		
		public function quick_report_pdf($report_Id){
			global  $wpdb;
			
			if(is_array($report_Id))
				$set_report_id = $report_Id[0];
			
			$report = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id='.$set_report_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			if($report->status=='3')
				{
				return '<a href="#" class="quickprint_report_to_pdf" id="'.$set_report_id.'"  title="" data-title="'.__('Export Report to PDF','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"><i class="fa-regular fa-file-pdf"></i></a>';
				}
			else
				{
				return '<i class="disabled fa-regular fa-file-pdf"></i>';
				}
			
		}
		
		public function link_form_title_3($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$title = $wpdb->get_var('SELECT title FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id='.$set_form_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			$title= wp_unslash($title);
			$title= str_replace('\"','',$title);
			$title= str_replace('"','',$title);
			$title= str_replace("\'",'',$title);
			$title= str_replace("'",'',$title);
			$title= str_replace('/','',$title);
			$title = sanitize_text_field( $title );
			
			return '<a href="#" id="'.$set_form_id.'" class="form_title get_form_fields"   title="'.__('Generate Report','nex-forms').'" data-title="'.__('Generate Report','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom">'.$title.'</a>';
		}
		
		public function get_form_shortcode($form_Id){
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
				
			return '[NEXForms id="'.$set_form_id.'"]';
		}
		
		
		public function print_export_form_link($form_Id){
			global  $wpdb;
			
			if(is_array($form_Id))
				$set_form_id = $form_Id[0];
			$title = $wpdb->get_var('SELECT title FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id='.$set_form_id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			$title= wp_unslash($title);
			$title= str_replace('\"','',$title);
			$title= str_replace('/','',$title);
			$title = sanitize_text_field( $title );
			
			return '<a href="'.get_option('siteurl').'/wp-admin/admin.php?page=nex-forms-dashboard&nex_forms_Id='.$set_form_id.'&export_form=true"    class="export_form" title="'.__('Export Form','nex-forms').'"><i class="fa fa-cloud-download" data-title="'.__('Export Form','nex-forms').'" data-toggle="tooltip_bs2" data-placement="bottom"></i></a>';
		}
		
		
		
		public function print_form_entry(){
			
			global $wpdb;
			$output = '';
			$output .= '<form id="form_save_form_entry" class="form_save_form_entry" name="save_form_entry" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">';
			$output .= '<div class="dashboard-box form_entry_view">';
				
				//<span class="header_text">'.__('Form Entry Data','nex-forms').'</span>
				/*$output .= '<div class="dashboard-box-header '.(($this->color_adapt) ? 'aa_bg_main' : '' ).'">';
					$output .= '<div class="table_title"><i class="material-icons header-icon">assignment_turned_in</i> </div>';
					
					
					$output .= '<a  class="cancel_save_form_entry save_button btn waves-effect waves-light" style="display:none;"><i class="fa fa-close"></i></a>';
					$output .= '<button type="submit" class="save_form_entry save_button btn waves-effect waves-light" style="display:none;">'.__('Save','nex-forms').'</button>';
					
					$output .= '<a class="btn waves-effect waves-light print_to_pdf" disabled="disabled">'.__('PDF','nex-forms').'</a>';
					$output .= '<a class="btn waves-effect waves-light print_form_entry" disabled="disabled">'.__('Print','nex-forms').'</a>';
					$output .= '<a id="" class="btn waves-effect waves-light edit_form_entry" disabled="disabled">'.__('Edit','nex-forms').'</a>';
				$output .= '</div>';*/
				$output .= '<div  class="dashboard-box-content form_entry_data">';
				
				$output .= '<table class="highlight" id="form_entry_table"></table>';//<thead><tr><th>'.__('Field Name','nex-forms').'</th><th>'.__('Field Value','nex-forms').'</th></tr></thead>
				
				$output .= '</div>';
					
			$output .= '</div>';
			$output .= '</form>';
			
			$output .= '<div class="form_entry_admin_email_view email_preview" style="display:none;">';
			$output .= '<iframe class="admin_email_view" src=""></iframe>';
			$output .= '</div>';
			
			$output .= '<div class="form_entry_user_email_view email_preview" style="display:none;">';
			$output .= '<iframe class="user_email_view" src=""></iframe>';
			$output .= '</div>';
			
			
			return $output;
		}
		
	public function do_form_entry_save(){
		
		if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_do_form_entry_save' ) ) {
				wp_die();
			}
		
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		global $wpdb;
		
		$edit_id = $wpdb->prepare('%d',esc_sql(sanitize_text_field($_POST['form_entry_id'])));
		$edit_id = str_replace('\'','',$edit_id);
		
		unset($_POST['nex_forms_wpnonce']);
		unset($_POST['action']);
		unset($_POST['submit']);
		unset($_POST['form_entry_id']);
		
		foreach($_POST as $key=>$val)
			{
			$data_array[] = array('field_name'=>$key,'field_value'=>sanitize_text_field($val));
			}
		//print_r($data_array);
		$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries',array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				'form_data'=>json_encode($data_array) // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		), array(	'Id' => sanitize_title($edit_id)) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		
		NEXForms_clean_echo( $edit_id);
		
		die();
		}
		
		
		
	public function submission_report(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			$set_additional_params = array();
			$nf_functions = new NEXForms_Functions();
			
			if($_POST['field_selection'])
				{
				$field_selection = isset($_POST['field_selection']) ? $_POST['field_selection'] : '';
				}
				
			//echo '<pre>test 1';
			//print_r($field_selection);
			//echo '</pre>';
			$records = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id=%d', sanitize_text_field($_POST['form_Id']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
			$get_temp_table_details = get_option('tmp_csv_export');
			update_option('tmp_csv_export',array('query'=>$get_temp_table_details['query'],'cols'=>$get_temp_table_details['cols'],'form_Id'=>sanitize_text_field($_POST['form_Id']))); 
			
			
			
			
			foreach($records as $data)
				{
				$form_values = json_decode($data->form_data);
				
				$header_array['entry_Id'] = $data->Id;
				
				$header_array['date_time'] = $data->date_time;
				
				foreach($form_values as $field)
					{
					if(is_array($field_selection))
						{
						if(in_array($field->field_name,$field_selection))
							{
							$header_array_filters[$field->field_name] = $nf_functions->unformat_records_name($field->field_name);
							}
						}
					else
						{
						$header_array_filters[$field->field_name] = $nf_functions->unformat_records_name($field->field_name);
						}
					$header_array[$field->field_name] = $nf_functions->unformat_records_name($field->field_name);
					}
				};
				
			
			
			
			if($wpdb->get_var("show tables like '".$wpdb->prefix."wap_nex_forms_temp_report'") == $wpdb->prefix.'wap_nex_forms_temp_report') // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				{
				$drop_table = 'DROP TABLE '.$wpdb->prefix.'wap_nex_forms_temp_report'; // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->query($drop_table); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
			$nf_functions = new NEXForms_Functions();
			
			$header_array2 = array_unique($header_array);
			$col_array_unique = array();
			foreach($header_array2 as $key => $val){
				if($key)
					$col_array_unique[$nf_functions->format_column_name($key)] = $nf_functions->format_column_name($key);
			}
			
			
			$sql .= 'CREATE TABLE `'.$wpdb->prefix.'wap_nex_forms_temp_report` (';	
					
					$sql .= '`Id` BIGINT(255) unsigned NOT NULL AUTO_INCREMENT,';
				
					foreach($col_array_unique as $key => $val){
						
						$col_name = $nf_functions->format_column_name($key);
						
						if($col_name!='')
							{
							if($col_name=='entry_id')
								$sql .= '`'.$col_name.'` BIGINT(255),';
							else
								$sql .= '`'.$col_name.'` longtext,';
							}
					}
				$sql .= 'PRIMARY KEY (`Id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
				
				$wpdb->query($sql); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
			
			  $table_fields 	= $wpdb->get_results('SHOW FIELDS FROM '.$wpdb->prefix.'wap_nex_forms_temp_report'); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			  foreach($records as $data)
					{
					$form_fields = json_decode($data->form_data);

					$column_array = array();
					
					$column_array['entry_Id'] = $data->Id;
					
					$column_array['date_time'] = $data->date_time;
					
					foreach($table_fields as $table_field)
						{
						foreach($form_fields as $form_field)
							{
							$form_field_name = $nf_functions->format_column_name($form_field->field_name);
							$table_field_col = $nf_functions->format_column_name($table_field->Field);
							
							if(is_array($form_field->field_value) || is_object($form_field->field_value))
								$form_field->field_value = json_encode($form_field->field_value);
							
							if($form_field_name==$table_field_col)
								{
								$column_array[$table_field_col] = $form_field->field_value;
								}
							}
						}
					$insert = $wpdb->insert ( $wpdb->prefix . 'wap_nex_forms_temp_report' , $column_array ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$insert_id = $wpdb->insert_id;
					}
			  foreach($col_array_unique as $key=>$val)
			  	{
				if(is_array($field_selection))
					{
					if(in_array($key,$field_selection))
						{
						$set_headers[$key]	= $key;
						$set_search[$key]	= $key;
						}
					}
				else
					{
					$set_headers[$key]	= $key;
					$set_search[$key]	= $key;
					}
				}
			
			  $database = new NEXForms_Database_Actions();

			  $report = new NEXForms_dashboard();
			  $report->table = 'wap_nex_forms_temp_report';
			  $report->extra_classes = 'wap_nex_forms_entries'; 
			  $report->table_header = 'Report';
			  $report->table_resize = true;
			  $report->table_header_icon = 'view_list';
			  $report->action_button = 'add';
			  $report->table_headings = $set_headers;
			  $report->show_headings=true;
			  $report->search_params = $set_search;
			  //$report->extra_buttons = array( 'Excel'=>array('class'=>'export-csv', 'type'=>'link','link'=>admin_url().'admin.php?page=nex-forms-dashboard&amp;export_excel=true', 'icon'=>'<span class="fa fa-file-excel"></span> '.__('Export to Excel','nex-forms').''), 'Export'=>array('class'=>'export-csv', 'type'=>'link','link'=>admin_url().'admin.php?page=nex-forms-dashboard&amp;export_csv=true', 'icon'=>'<span class="fa fa-file-excel"></span> '.__('Export to Excel(CSV)','nex-forms').''), 'PDF'=>array('class'=>'print_report_to_pdf', 'type'=>'button','link'=>'', 'icon'=>'<span class="fa fa-file-pdf"></span> '.__('Export to PDF','nex-forms').'')); //'Report'=>array('class'=>'run_query', 'id'=>$_POST['form_Id'], 'type'=>'button','link'=>'', 'icon'=>'<span class="fa fa-filter"></span> '.__('Build Report','nex-forms').''),
			  $report->extra_buttons = array( 'Export'=>array('class'=>'export-csv', 'type'=>'link','link'=>admin_url().'admin.php?page=nex-forms-dashboard&amp;export_csv=true', 'icon'=>'<span class="fa fa-file-excel"></span> '.__('Export to Excel(CSV)','nex-forms').''), 'PDF'=>array('class'=>'print_report_to_pdf', 'type'=>'button','link'=>'', 'icon'=>'<span class="fa fa-file-pdf"></span> '.__('Export to PDF','nex-forms').'')); //'Report'=>array('class'=>'run_query', 'id'=>$_POST['form_Id'], 'type'=>'button','link'=>'', 'icon'=>'<span class="fa fa-filter"></span> '.__('Build Report','nex-forms').''),
			  $report->checkout = $database->checkout();
			  $report->is_report = true;
			  $report->show_delete = false;
			  $report->color_adapt = true;
			  $report->record_limit = 100;
			  
			  if($_POST['field_selection'])
			 	 $report->field_selection = $_POST['field_selection'];
			  $report->additional_params = $_POST['additional_params'];
			 $output .= '<div class="right-col-top">'; 
			 
			 	$output .= '<div class="right-col-inner aa_bg_tri">'; 
			 
			 		
				//$output .= $report->print_form_entry();
				
				
				
				$output .= '<div class="reporting_controls">';
				
				$show_cols = $_POST['showhide_fields'];
				
				$output .= '<div class="col-sm-3 field_selection_col ">';
				$output .= '<select name="showhide_fields[]" multiple="multiple" class="aa_multi_select field_selection_multi_select">
							<option disabled="disabled">'.__('Show Fields','nex-forms').'</option>
				';
				$show_cols = explode(',',$show_cols);
				$i = 0;
				 
				if($_POST['field_selection'])
					{
					$field_selection = isset($_POST['field_selection']) ? $_POST['field_selection'] : '';
					}
				//else
					//$field_selection = $this->field_selection;
				 
				 foreach($col_array_unique as $key=>$val)
					{
					if(is_array($field_selection))
						{
						$output .= '<option value="'.$key.'" '.((in_array($key,$field_selection)) ? 'selected="selected"' : '').'>
								'. $nf_functions->unformat_records_name($val,30).'</option>';
						}
					else
						{
						$output .= '<option value="'.$key.'"  selected="selected">
								'.$nf_functions->unformat_records_name($val,30).'</option>';	
						}
					$i++;
					}
					$output .= '</select></div>';
				 $output .= '<div class="col-sm-1 add_clause">';
						$output .= '<a class="nf_button aa_bg_main_btn add_new_where_clause2"><i class="fa fa-filter"></i> Add filter </a>';
						
						
						$output .= '<a class="nf_button aa_bg_main_btn run_query_2 run_query" id="'.sanitize_text_field($_POST['form_Id']).'"><i class="fa fa-file-import"></i> Run Query </a>';
							
					 $output .= '</div>';
				
				$output .= '<div class="clause_container zero_padding">';
				
				foreach($_POST['additional_params'] as $key=>$val)
					{
						
					$output .= '<div class="new_clause">';
					$output .= '<div class="col-xs-4 zero_padding">';
						$output .= '<select class="post_ajax_select aa_bg_main_input form_control" name="column">
									  <option value="">'.__('--- Select field ---','nex-forms').'</option>';
										foreach($header_array_filters as $key2=>$val2)
											$output .= ' <option value="'.$key2.'" '.(($val['column']==$key2) ? 'selected="selected"' : '').'>'.$val2.'</option>';
						$output .= '</select>';	
					 $output .= '</div>';
					 
					 $output .= '<div class="col-xs-3">';
						$output .= '
									<select class="post_ajax_select aa_bg_main_input form_control" name="operator">
									  <option value="=" 		'.(($val['operator']=='=') 			? 'selected="selected"' : '').'>'.__('Equal to','nex-forms').'</option>
									  <option value="<>" 		'.(($val['operator']=='<>') 		? 'selected="selected"' : '').'>'.__('Not equal','nex-forms').'</option>
									  <option value=">" 		'.(($val['operator']=='>') 			? 'selected="selected"' : '').'>'.__('Greater than','nex-forms').'</option>
									  <option value="<" 		'.(($val['operator']=='<') 			? 'selected="selected"' : '').'>'.__('Less than','nex-forms').'</option>
									  <option value="LIKE" 		'.(($val['operator']=='LIKE') 		? 'selected="selected"' : '').'>'.__('Contains','nex-forms').'</option>
									  <option value="NOT LIKE" 	'.(($val['operator']=='NOT LIKE') 	? 'selected="selected"' : '').'>'.__('Does not contain','nex-forms').'</option>
									  ';
						$output .= '</select>';	
					$output .= '</div>';
					
					$output .= '<div class="col-xs-4 zero_padding">';
						$output .= '<input name="column_value" class="form-control aa_bg_main_input" placeholder="'.__('Value','nex-forms').'" value="'.$val['value'].'">';	
					 $output .= '</div>';
					 
					 $output .= '<div class="col-xs-1 zero_padding">';
						$output .= '<a class="btn remove_where_clause">X</a>';	
					 $output .= '</div>';
				$output .= '</div>';
						
					$set_additional_params[$val['column']] = $val['value'];
					}
				
				$output .= '</div>';
				
				$output .= '<div class="clause_replicator hidden">';
					$output .= '<div class="col col-xs-4 zero_padding">';
						$output .= '
									<select class="post_ajax_select form_control aa_bg_main_input" name="column">
									  <option value="" selected="selected">'.__('--- Select field ---','nex-forms').'</option>';
										foreach($header_array_filters as $key=>$val)
											$output .= ' <option value="'.$key.'">'.$val.'</option>';
						$output .= '</select>';	
					 $output .= '</div>';
					 
					 $output .= '<div class="col col-xs-3">';
						$output .= '
									<select class="post_ajax_select form_control aa_bg_main_input" name="operator">
									  <option value="=">'.__('Equal to','nex-forms').'</option>
									  <option value="<>">'.__('Not equal','nex-forms').'</option>
									  <option value=">">'.__('Greater than','nex-forms').'</option>
									  <option value="<">'.__('Less than','nex-forms').'</option>
									  <option value="LIKE">'.__('Contains','nex-forms').'</option>
									  <option value="NOT LIKE">'.__('Does not contain','nex-forms').'</option>
									  ';
						$output .= '</select>';	
					$output .= '</div>';
					
					$output .= '<div class="col col-xs-4 zero_padding">';
						$output .= '<input name="column_value" class="form-control aa_bg_main_input" placeholder="'.__('Value','nex-forms').'">';	
					 $output .= '</div>';
					 
					 $output .= '<div class="col col-xs-1 zero_padding">';
						$output .= '<a class="btn remove_where_clause">X</a>';	
					 $output .= '</div>';
				$output .= '</div>';
				
				$output .= '</div>';
				//$output .= '<a class="btn run_query hidden" id="'.$_POST['form_Id'].'"><span class="fa fa-filter"></span> '.__('Run Report','nex-forms').'</a>';
				
				  
				$output .= '</div>';
					
				$output .= '</div>';
			$output .= '</div>';
			/*$output .= '<div class="right-mid">';
					
					
					
					$output .= '<div class="entry_tools aa_bg_main">';
						
						$output .= '<button type="submit" class="save_form_entry save_button button button-primary" style="display:none;">'.__('Save','nex-forms').'</button>';
						$output .= '<button class="cancel_save_form_entry save_button button button-primary" style="display:none;"><i class="fa fa-close"></i></button>';
						
						
						$output .= '<div class="entry_views">';
						
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch  view_form_data active" data-action="view-data" disabled="disabled"><span class="fas fa-database"></span> '.__('Entry Data','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch" data-action="view-admin-email" disabled="disabled"><span class="fas fa-envelope"></span> '.__('View Admin Email','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action no_batch" data-action="view-user-email" disabled="disabled"><span class="far fa-envelope"></span> '.__('View User Email','nex-forms').'</button>';
						$output .= '</div>';
					
						$output .= '<div class="entry_actions">';	
							$output .= '<button class="print_to_pdf aa_bg_main_btn no_batch nf_button" disabled="disabled"><span class="fas fa-file-pdf"></span> '.__('Export to PDF','nex-forms').'</button>';
							//$output .= '<button class="button no_batch do_action" data-action="print-form-entry" disabled="disabled"><span class="fas fa-print"></span> '.__('Print','nex-forms').'</button>';
							$output .= '<button id="" class="edit_form_entry aa_bg_main_btn no_batch nf_button" disabled="disabled"><span class="fas fa-pen-square"></span> '.__('Edit','nex-forms').'</button>';
							$output .= '<button class="nf_button aa_bg_main_btn do_action" data-action="delete" disabled="disabled"><span class="fas fa-trash"></span> '.__('Delete','nex-forms').'</button>';
						$output .= '</div>';
						
					$output .= '</div>';
					
				$output .= '</div>';*/
			
			$output .= '<div class="right-bottom">';
			$output .= $report->print_record_table();
					
				$output .= '</div>';
			NEXForms_clean_echo( $output);
			die();
		}
	
	
	public function submission_report2(){
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			global $wpdb;
			
			$set_additional_params = array();
			$nf_functions = new NEXForms_Functions();
			
			if($_POST['field_selection'])
				{
				$field_selection = isset($_POST['field_selection']) ? $_POST['field_selection'] : '';
				}
			
			$refresh = isset($_POST['refresh_data']) ? true : false;
				
			$db_table = $wpdb->get_var($wpdb->prepare('SELECT db_table FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id=%d', sanitize_title($_POST['report_update_id']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
			
			$db_table = str_replace($wpdb->prefix,'',$db_table);
			
			/*echo '<pre>';
			print_r($field_selection);
			echo '</pre>';	*/
			
			$set_report_id = (isset($_POST['report_update_id'])) ? $wpdb->prepare('%d',esc_sql(sanitize_title($_POST['report_update_id']))) : false;
			
			$tz = wp_timezone();
			$set_date = new DateTime("now", $tz);
			
			if($set_report_id)
				{
				$update = $wpdb->update ( $wpdb->prefix.'wap_nex_forms_reports', array // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					(
					'report_fields'=>json_encode($field_selection),
					'status'=>'3',
					'date_time'			=> $set_date->format('Y-m-d H:i:s'),
					), array(	'Id' => $set_report_id) ); 
					
				}
			$records = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.$wpdb->prefix.'wap_nex_forms_entries` WHERE `nex_forms_Id`=%d ORDER BY `last_update` DESC LIMIT 500 OFFSET 0', sanitize_text_field($_POST['form_Id']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
			$count_records = $wpdb->get_var($wpdb->prepare('SELECT count(*) FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id=%d', sanitize_text_field($_POST['form_Id']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			
			
			$get_temp_table_details = get_option('tmp_csv_export');
			update_option('tmp_csv_export',array('query'=>$get_temp_table_details['query'],'cols'=>$get_temp_table_details['cols'],'form_Id'=>sanitize_text_field($_POST['form_Id']))); 
			
			
			
			
			foreach($records as $data)
				{
				$form_values = json_decode($data->form_data);
				
				$header_array['entry_Id'] = $data->Id;
				
				$header_array['date_time'] = $data->date_time;
				
				$header_array_filters['entry_Id'] = $data->Id;
				
				$header_array_filters['date_time'] = $data->date_time;
				
				foreach($form_values as $field)
					{
					if(is_array($field_selection))
						{
						//echo $nf_functions->format_name($field->field_name).'<br />';
						if(in_array($nf_functions->format_column_name($field->field_name),$field_selection))
							{
							$header_array_filters[$field->field_name] = $nf_functions->format_column_name($field->field_name);
							}
						}
					else
						{
						$header_array_filters[$field->field_name] = $nf_functions->format_column_name($field->field_name);
						}
					$header_array[$field->field_name] = $nf_functions->format_column_name($field->field_name);
					}
				};
				
			
			
			
			
			$nf_functions = new NEXForms_Functions();
			
			$header_array2 = array_unique($header_array_filters);
			$col_array_unique = array();
			foreach($header_array2 as $key => $val){
				if($key)
					{
					$col_array_unique[$nf_functions->format_column_name($key)] = $nf_functions->format_column_name($key);
					
					}
			}
			
			
			/*echo '<pre>';
			print_r($header_array_filters);
			echo '</pre>';*/
			$sql = '';
			if(!$refresh)
				{
				if($wpdb->get_var("show tables like '".$wpdb->prefix.$db_table."'") == $wpdb->prefix.$db_table) // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					{
					$drop_table = 'DROP TABLE '.$wpdb->prefix.$db_table; // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query($drop_table); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					}
				$charset_collate = $wpdb->get_charset_collate();
				
				$sql .= 'CREATE TABLE `'.$wpdb->prefix.$db_table.'` (';	
						
						$sql .= '`Id` BIGINT(255) unsigned NOT NULL AUTO_INCREMENT,';
					
						foreach($col_array_unique as $key => $val){
							
							$col_name = $nf_functions->format_column_name($key);
							
							if($col_name!='')
								{
								if($col_name=='entry_id')
									$sql .= '`'.$col_name.'` BIGINT(255),';
								else
									$sql .= '`'.$col_name.'` longtext,';
								}
						}
					$sql .= 'PRIMARY KEY (`Id`)
						) '.$charset_collate.';';
					
					$wpdb->query($sql); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				
				// echo '##########'.$sql;
				
				  $table_fields 	= $wpdb->get_results('SHOW FIELDS FROM '.$wpdb->prefix.$db_table); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					
				  $itteration = round(($count_records/100)+1);
				  
				  for($i=0;$i<=$itteration;$i++)
					{
					$records = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id=%d LIMIT 100 OFFSET '.($i*100).'', sanitize_text_field($_POST['form_Id']))); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					
					foreach($records as $data)
						{
						$form_fields = json_decode($data->form_data);
						
						$column_array = array();
						
						$column_array['entry_Id'] = $data->Id;
						
						$column_array['date_time'] = $data->date_time;
						
						foreach($table_fields as $table_field)
							{
							foreach($form_fields as $form_field)
								{
								$form_field_name = $nf_functions->format_column_name($form_field->field_name);
								$table_field_col = $nf_functions->format_column_name($table_field->Field);
								$array_field_val = '';
								if(is_array($form_field->field_value) || is_object($form_field->field_value))
									{
									foreach($form_field->field_value as $field => $val)
										{
											if (is_object($val) || is_array($val)) {
												$val = json_encode($val); // Convert object/array to a JSON string
											}
											$array_field_val .= $val.' - ';    
										}
									$form_field->field_value = rtrim($array_field_val,' - ');
									}
								if($form_field_name==$table_field_col)
									{
									$column_array[$table_field_col] = $form_field->field_value;
									}
								}
							}
						
						$insert = $wpdb->insert ( $wpdb->prefix . $db_table , $column_array ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						//$insert_id = $wpdb->insert_id;
						}
					}
				}
			else
				{
				if($set_report_id)
					{
					$update = $wpdb->update ( $wpdb->prefix.'wap_nex_forms_reports', array // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						(
						'report_params'=>json_encode($_POST['additional_params']),
						
						), array(	'Id' => $set_report_id) ); 
						
					}	
				}
				
				
			  foreach($col_array_unique as $key=>$val)
			  	{
				if(is_array($field_selection))
					{
					if(in_array($key,$field_selection))
						{
						$set_headers[$key]	= $key;
						$set_search[$key]	= $key;
						}
					}
				else
					{
					$set_headers[$key]	= $key;
					$set_search[$key]	= $key;
					}
				}
			
			  $database = new NEXForms_Database_Actions();

			  $report = new NEXForms_dashboard();
			  $report->table = $db_table;
			  $report->extra_classes = 'wap_nex_forms_entries'; 
			  $report->table_header = '';
			  $report->table_resize = true;
			  $report->table_header_icon = 'view_list';
			  $report->action_button = 'add';
			  $report->table_headings = $set_headers;
			  $report->show_headings=true;
			  $report->search_params = $set_search;
			  $report->extra_buttons = array(  'Filters'=>array('class'=>'open_reporting_filters', 'type'=>'button','link'=>'', 'icon'=>'<span class="fas fa-filter"></span> '.__('Filters','nex-forms').' <span class="total_filters"></span>'), 'Export'=>array('class'=>'export-csv', 'type'=>'link','link'=>admin_url().'admin.php?page=nex-forms-dashboard&amp;export_csv=true&amp;report_Id='.$set_report_id, 'icon'=>'<span class="fa fa-file-excel"></span> '.__('Export to Excel(CSV)','nex-forms').''), 'PDF'=>array('class'=>'print_report_to_pdf', 'type'=>'button','link'=>'', 'icon'=>'<span class="fa fa-file-pdf"></span> '.__('Export to PDF','nex-forms').'')); //'Report'=>array('class'=>'run_query', 'id'=>$_POST['form_Id'], 'type'=>'button','link'=>'', 'icon'=>'<span class="fa fa-filter"></span> '.__('Build Report','nex-forms').''),
			  $report->checkout = $database->checkout();
			  $report->is_report = true;
			  $report->show_delete = false;
			  $report->color_adapt = true;
			  $report->record_limit = 100;
			  
			  
			  $set_field_selection = array();
				
				$db_cols = array();
				foreach($table_fields as $col)
					{
						if($col->Field!='Id')
							{
							if(in_array($col->Field,$field_selection))
								$set_field_selection[] = $col->Field;
							}
					}
				$field_selection = $set_field_selection;
				if(!empty($diff))
					{
					foreach($diff as $no)
						{
						$unconverted_fields .= ' '.$no.',';
						}
					NEXForms_clean_echo( '<div class="alert alert-info">The following fields could not be converted into MySQL table columns: '.$unconverted_fields.'</div>');
					//	print_r($diff);
					//echo '</pre>';
					}
				
			  
			  if($_POST['field_selection'])
			 	 $report->field_selection = $field_selection;
				 
			  $report->additional_params = $_POST['additional_params'];
			 	
				$output .= '<div class="add_clause">';
					$output .= '<a class="nf_button aa_bg_sec_btn add_new_where_clause2"><i class="fa fa-plus"></i> Add Filter </a>';
					$output .= '<a class="nf_button aa_bg_sec_btn run_query_2 run_query" id="'.sanitize_text_field($_POST['form_Id']).'"><i class="fa fa-file-import"></i> Run Query </a>';
					$output .= '<div class="close_filters"><span class="fas fa-arrow-left"></span></div>';
				$output .= '</div>';
				
				$output .= '<div class="right-col-top">';
				$output .= '</div>';
			/*echo '<pre>';
				print_r($field_selection);
			echo '</pre>';*/
			
			
			if($_POST['field_selection'])
			 	 $report->field_selection = $_POST['field_selection'];
			  $report->additional_params = $_POST['additional_params'];
			 
			
			
			
			
			
			
			$output .= '<div class="right-bottom">';
			
			
			if(!$database->checkout())
				{
				$output .= '<div class="alert alert-danger" style="margin:20px;"><span class="fas fa-lock"></span> PREMIUM ONLY FEATURE: An active premium license is required to view submission reports. <a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock"" class="upgrade-link" target="_blank"> Upgrade to Premium <span class="fa-solid fa-angles-up"></span></a></div>'	;
				}
			else
				{
				$output .= $report->print_record_table();
				}
			$output .= '</div>';
			
			NEXForms_clean_echo( $output);
			wp_die();
		}
	
	public function report_get_additional_params($return = false, $update_id = 0)
		{
		
		$nf_functions = new NEXForms_Functions();
		global $wpdb;
		$output = '';
		
		$set_update_id = ($update_id) ? $update_id : sanitize_title($_POST['report_update_id']); 
		
		$report = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_reports WHERE Id=%d', $set_update_id)); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		
		
		$additional_params = ($set_update_id) ? json_decode($report->report_params,true) : $_POST['additional_params']; 
		
		//if($additional_params)
				//{
				$set_params = isset($additional_params) ? $additional_params : '';
				if(!is_array($set_params))
					$additional_params = json_decode(str_replace('\\','',$set_params),true);
				else
					$additional_params = $wpdb->prepare('%s',esc_sql($set_params));
				//}
			//else
			//	$additional_params = $this->additional_params;

		
		
		
		
		
		
		 $header_array_filters = json_decode($report->report_fields,true);
		 
		 
		/* echo '<pre>';
		 	print_r($header_array_filters);
		 echo '</pre>';*/
		 
			 	$output .= '<div class="right-col-inner aa_bg_tri">'; 
			 
				
				$output .= '<div class="reporting_controls">';
				
				
				 
				
				$output .= '<div class="clause_container col-sm-12">';
				if(isset($additional_params))
					{	
					foreach($additional_params as $key=>$val)
						{
							
						$output .= '<div class="new_clause">';
						$output .= '<div class="col-xs-4 zero_padding">';
							$output .= '<select class="post_ajax_select aa_bg_main_input form_control" name="column">
										  <option value="">'.__('--- Select field ---','nex-forms').'</option>';
											foreach($header_array_filters as $key2=>$val2)
												$output .= ' <option value="'.$val2.'" '.(($val['column']==$val2) ? 'selected="selected"' : '').'>'.$nf_functions->unformat_name($val2).'</option>';
							$output .= '</select>';	
						 $output .= '</div>';
						 
						 $output .= '<div class="col-xs-3">';
							$output .= '
										<select class="post_ajax_select aa_bg_main_input form_control" name="operator">
										  <option value="=" 		'.(($val['operator']=='=') 			? 'selected="selected"' : '').'>'.__('Equal to','nex-forms').'</option>
										  <option value="<>" 		'.(($val['operator']=='<>') 		? 'selected="selected"' : '').'>'.__('Not equal','nex-forms').'</option>
										  <option value=">" 		'.(($val['operator']=='>') 			? 'selected="selected"' : '').'>'.__('Greater than','nex-forms').'</option>
										  <option value="<" 		'.(($val['operator']=='<') 			? 'selected="selected"' : '').'>'.__('Less than','nex-forms').'</option>
										  <option value="LIKE" 		'.(($val['operator']=='LIKE') 		? 'selected="selected"' : '').'>'.__('Contains','nex-forms').'</option>
										  <option value="NOT LIKE" 	'.(($val['operator']=='NOT LIKE') 	? 'selected="selected"' : '').'>'.__('Does not contain','nex-forms').'</option>
										  ';
							$output .= '</select>';	
						$output .= '</div>';
						
						$output .= '<div class="col-xs-4 zero_padding">';
							$output .= '<input name="column_value" class="form-control aa_bg_main_input" placeholder="'.__('Value','nex-forms').'" value="'.$val['value'].'">';	
						 $output .= '</div>';
						 
						 $output .= '<div class="col-xs-1 zero_padding">';
							$output .= '<a class="btn remove_where_clause">X</a>';	
						 $output .= '</div>';
						$output .= '</div>';
							
						$set_additional_params[$val['column']] = $val['value'];
						}
					}
				
				$output .= '</div>';
				

				$output .= '<div class="clause_replicator hidden">';
					$output .= '<div class="col col-xs-4 zero_padding">';
						$output .= '<select class="post_ajax_select form_control aa_bg_main_input" name="column">
									  <option value="" selected="selected">'.__('--- Select field ---','nex-forms').'</option>';
										foreach($header_array_filters as $key=>$val)
											$output .= ' <option value="'.$val.'">'.$nf_functions->unformat_name($val).'</option>';
						$output .= '</select>';	
					 $output .= '</div>';
					 
					 $output .= '<div class="col col-xs-3">';
						$output .= '
									<select class="post_ajax_select form_control aa_bg_main_input" name="operator">
									  <option value="=">'.__('Equal to','nex-forms').'</option>
									  <option value="<>">'.__('Not equal','nex-forms').'</option>
									  <option value=">">'.__('Greater than','nex-forms').'</option>
									  <option value="<">'.__('Less than','nex-forms').'</option>
									  <option value="LIKE">'.__('Contains','nex-forms').'</option>
									  <option value="NOT LIKE">'.__('Does not contain','nex-forms').'</option>
									  ';
						$output .= '</select>';	
					$output .= '</div>';
					
					$output .= '<div class="col col-xs-4 zero_padding">';
						$output .= '<input name="column_value" class="form-control aa_bg_main_input" placeholder="'.__('Value','nex-forms').'">';	
					 $output .= '</div>';
					 
					 $output .= '<div class="col col-xs-1 zero_padding">';
						$output .= '<a class="btn remove_where_clause">X</a>';	
					 $output .= '</div>';
				$output .= '</div>';
				
				$output .= '</div>';
				
				  
				$output .= '</div>';
					
				$output .= '</div>';
				
			
			if($return)
				return $output;
			else
				{
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				wp_die();
				}
		}
	
		
	public function	print_to_pdf()
		{
		//if(!current_user_can( NF_USER_LEVEL ))	
		//		wp_die();
		if (function_exists('NEXForms_export_to_PDF'))
			{
			NEXForms_clean_echo( NEXForms_export_to_PDF(sanitize_text_field($_POST['form_entry_Id']), true, true));
			}
		else
			{
			NEXForms_clean_echo( 'not installed');
			die();	
			}
		}
	
	public function	delete_pdf()
		{
		
		if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
			
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		
		$upload_path = wp_upload_dir();
		$set_uploads_dir = $upload_path['path'];
		
		unlink($upload_path['baseurl'].$upload_path['subdir'].'/form_entry.pdf');
		unlink($set_uploads_dir.'/form_entry.pdf');
		
		unlink($upload_path['baseurl'].$upload_path['subdir'].'/submission_report.pdf');
		unlink($set_uploads_dir.'/submission_report.pdf');
		
		die();	
			
		}
	
	
	public function	print_report_to_pdf()
		{
		if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
		if (function_exists('NEXForms_report_to_PDF'))
			{
			NEXForms_clean_echo( NEXForms_report_to_PDF(sanitize_title($_POST['report_Id'])));
			}
		else
			{
			NEXForms_clean_echo( 'not installed');
			die();	
			}
		}
	
	
	public function email_setup(){
		$email_config = get_option('nex-forms-email-config');
		$output = '';	
		$theme = wp_get_theme();
		$output .= '<div class="dashboard-box global_settings">';
			$output .= '<div class="dashboard-box-header aa_bg_main">';
				$output .= '<div class="table_title"><i class="material-icons header-icon">drafts</i><span class="header_text ">'.__('Mailer Config','nex-forms').'</span></div>';
			$output .= '</div>';
			
			$output .= '<div  class="dashboard-box-content">';
				$output .= '<form name="email_config" id="email_config" action="'.admin_url('admin-ajax.php').'" method="post">		
							
								
									<div class="row">
										<div class="col-sm-4">'.__('Email Format','nex-forms').'</div>
										<div class="col-sm-8">
											<input type="radio" '.(($email_config['email_content']=='html' || !$email_config['email_content']) ? 	'checked="checked"' : '').' name="email_content" value="html" 	id="html" class="with-gap"><label for="html">HTML</label>
											<input type="radio" '.(($email_config['email_content']=='pt') ? 	'checked="checked"' : '').' name="email_content" value="pt" 	id="pt"	class="with-gap"><label for="pt">Plain Text</label>
										</div>
									</div>
									
									<div class="row">
										<div class="col-sm-4">'.__('Mailing Method','nex-forms').'</div>
										<div class="col-sm-8">
											<input type="radio" '.(($email_config['email_method']=='wp_mailer' || $email_config['email_method']=='api') ? 	'checked="checked"' : '').' name="email_method" value="wp_mailer" 	id="wp_mailer"	class="with-gap"><label for="wp_mailer">WP Mail <span class="alert alert-success" style="
    padding: 0px 10px 2px 10px;
    font-size: 11px;
    margin-left: 6px;
">Recomended</span></label><br />
											<input type="radio" '.((!$email_config['email_method'] || $email_config['email_method']=='php_mailer') ? 	'checked="checked"' : '').' name="email_method" value="php_mailer" 	id="php_mailer"	class="with-gap"><label for="php_mailer">PHP Mailer</label><br />
											<input type="radio" '.(($email_config['email_method']=='php') ? 		'checked="checked"' : '').' name="email_method" value="php" 		id="php"		class="with-gap"><label for="php">Normal PHP</label><br />
											<input type="radio" '.(($email_config['email_method']=='smtp') ? 		'checked="checked"' : '').' name="email_method" value="smtp" 		id="smtp"		class="with-gap"><label for="smtp">SMTP</label><br />
											
										</div>
									</div>
									
									<div class="smtp_settings" '.(($email_config['email_method']!='smtp') ? 		'style="display:none;"' : '').'>
										<h5>'.__('SMTP Setup','nex-forms').'</h5>
										<div class="row">
											<div class="col-sm-4">'.__('Host','nex-forms').'</div>
											<div class="col-sm-8">
												<input class="form-control" type="text" name="smtp_host" placeholder="'.__('eg: mail.gmail.com','nex-forms').'" value="'.$email_config['smtp_host'].'">
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-4">'.__('Port','nex-forms').'</div>
											<div class="col-sm-8">
												<input class="form-control" type="text" name="mail_port" placeholder="'.__('likely to be 25, 465 or 587','nex-forms').'" value="'.$email_config['mail_port'].'">
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-4">'.__('Security','nex-forms').'</div>
											<div class="col-sm-8">
												<input type="radio" '.(($email_config['email_smtp_secure']=='0' || !$email_config['email_smtp_secure']) ? 	'checked="checked"' : '').' name="email_smtp_secure" value="0" id="none" class="with-gap"><label for="none">'.__('None','nex-forms').'</label>
												<input type="radio" '.(($email_config['email_smtp_secure']=='ssl') ? 	'checked="checked"' : '').'  name="email_smtp_secure" value="ssl" id="ssl" class="with-gap"><label for="ssl">SSL</label>
												<input type="radio" '.(($email_config['email_smtp_secure']=='tls') ? 	'checked="checked"' : '').'  name="email_smtp_secure" value="tls" id="tls" class="with-gap"><label for="tls">TLS</label>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-4">'.__('Authentication','nex-forms').'</div>
											<div class="col-sm-8">
												<input type="radio" '.(($email_config['smtp_auth']=='1') ? 	'checked="checked"' : '').'  name="smtp_auth" value="1" 		id="auth_yes"		class="with-gap"><label for="auth_yes">Use Authentication</label>
												<input type="radio" '.(($email_config['smtp_auth']=='0') ? 	'checked="checked"' : '').'  name="smtp_auth" value="0" 		id="auth_no"		class="with-gap"><label for="auth_no">No Authentication</label>
											</div>
										</div>
										
									</div>
									
									<div class="smtp_auth_settings" '.(($email_config['email_method']!='smtp' || $email_config['smtp_auth']!='1') ? 		'style="display:none;"' : '').'>
										<h5>'.__('SMTP Authentication','nex-forms').'</h5>
										<div class="row">
											<div class="col-sm-4">'.__('Username','nex-forms').'</div>
											<div class="col-sm-8">
												<input class="form-control" type="text" name="set_smtp_user" value="'.$email_config['set_smtp_user'].'">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-4">'.__('Password','nex-forms').'</div>
											<div class="col-sm-8">
												<input class="form-control" type="password" name="set_smtp_pass" value="'.$email_config['set_smtp_pass'].'">
											</div>
										</div>
									</div>
									
									
										<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save Mailer Config','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
										<div style="clear:both;"></div>
									
									
										
								
					</form></div>';
			
		$output .= '<div class="dashboard-box-footer">
											<input type="text" class="form-control" name="test_email_address" value="" placeholder="'.__('Enter Email Address','nex-forms').'">
										
											<div class="btn blue waves-effect waves-light send_test_email full_width">'.__('Send Test Email','nex-forms').'</div>
											<div style="clear:both"></div>
										</div></div>';
		return $output;
	}
	
	
	public function email_subscriptions_setup(){
		
		$output = '';
			$output .= '<div class="dashboard-box global_settings ">';
							$output .= '<div class="dashboard-box-header aa_bg_main">';
								$output .= '<div class="table_title"><i class="material-icons header-icon contact_mail">contact_mail</i><span class="header_text ">'.__('Email Subscriptions Setup','nex-forms').'</span></div>';
								$output .= '
								<nav class="nav-extended dashboard_nav dashboard-box-nav">
									<div class="nav-content aa_bg_sec">
									  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu">
										<li class="tab"><a class="active" href="#mail_chimp">'.__('MailChimp','nex-forms').'</a></li>
										<li class="tab"><a href="#get_response">'.__('GetResponse','nex-forms').'</a></li>
									  </ul>
									</div>
								 </nav>';
							$output .= '</div>';
							
							$output .= '<div  class="dashboard-box-content">';
								$output .= '<div id="mail_chimp">';
									$output .= $this->print_mailchimp_setup();
								$output .= '</div>';
								
								$output .= '<div id="get_response" style="display:none;">';
									$output .= $this->print_getresponse_setup();
								$output .= '</div>';
								
							$output .= '</div>';
						$output .= '</div>';
		return $output;
	}
	
	public function print_mailchimp_setup(){
		
		$output = '';	
		$theme = wp_get_theme();
		$output .= '
				<form name="mail_chimp_setup" id="mail_chimp_setup" action="'.admin_url('admin-ajax.php').'" method="post">
					<div class="row">
						<div class="col-sm-4">'.__('Mailchimp API key','nex-forms').'</div>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="mc_api" value="'.(($theme->Name=='NEX-Forms Demo') ? '&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;' : get_option('nex_forms_mailchimp_api_key')).'" id="mc_api" placeholder="Enter your Mailchimp API key">
						</div>
					</div>
					<div class="alert alert-info">
						'.__('<strong>How to get your Mailchimp API key:</strong>
						<ol>
							<li>Login to your Mailchimp account: <a href="http://mailchimp.com/" target="_blank">mailchimp.com</a></li>
							<li>Click on your profile picture (top right of the screen)</li>
							<li>From the dropdown Click on Account</li>
							<li>Click on Extras->API Keys</li>
							<li>Copy your API key, or create a new one</li>
							<li>Paste your API key in the above field.</li>
							<li>Save</li>
						</ol>','nex-forms').'
					</div>
					
					
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save MailChimp API','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
					';
		
		
		return $output;
	}
	
	public function print_getresponse_setup(){
		
		$output = '';	
		$theme = wp_get_theme();
		$output .= '
				<form name="get_response_setup" id="get_response_setup" action="'.admin_url('admin-ajax.php').'" method="post">
					<div class="row">
						<div class="col-sm-4">'.__('GetResponse API key','nex-forms').'</div>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="gr_api" value="'.(($theme->Name=='NEX-Forms Demo') ? '&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;' : get_option('nex_forms_get_response_api_key')).'" id="gr_api" placeholder="Enter your GetResponse API key">
						</div>
					</div>
					<div class="alert alert-info">
						'.__('<strong>How to get your GetReponse API key:</strong>
						<ol>
							<li>Login to your GetResponse account: <a href="https://app.getresponse.com/" target="_blank">GetResponse</a></li>
							<li>Hover over your profile picture (top right of the screen)</li>
							<li>From the dropdown Click on Integrations</li>
							<li>Click on API &amp; OAuth</li>
							<li>Copy your API key, or create a new one</li>
							<li>Paste your API key in the above field.</li>
							<li>Save</li>
						</ol>','nex-forms').'
					</div>
					
					
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save GetResponse API','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
					';
		
		return $output;
	}
	
	
	
	public function wp_admin_options(){
		$other_config = get_option('nex-forms-other-config');
		
		
		$user_config = get_user_option('nex-forms-user-config',get_current_user_id());
		
		$theme = wp_get_theme();
		$output = '';	
		$output .= '<div class="dashboard-box global_settings">';
			$output .= '<div class="dashboard-box-header aa_bg_main">';
				$output .= '<div class="table_title"><i class="material-icons header-icon">accessibility</i><span class="header_text ">'.__('WP Admin Accessibility Options','nex-forms').'</span></div>';
			$output .= '</div>';
			
			$output .= '<div  class="dashboard-box-content">';
			if($theme->Name!='NEX-Forms Demo')
				$output .= '<form name="other_config" id="other_config" action="'.admin_url('admin-ajax.php').'" method="post">';
							
							//echo '######'.$user_config['enable-color-adapt'];	
				$output .= '	<div class="row">
									<div class="col-sm-6">'.__('NEX-Forms User Level','nex-forms').'</div>
									<div class="col-sm-6">
										
										<select name="set-wp-user-level" id="set-wp-user-level" class="material_select_1 form-control" style="display:block !important;">
											<option '.(($other_config['set-wp-user-level']=='subscriber') ? 	'selected="selected"' : '').'  value="subscriber">'.__('Subscriber','nex-forms').'</option>
											<option '.(($other_config['set-wp-user-level']=='contributor') ? 	'selected="selected"' : '').' value="contributor">'.__('Contributor','nex-forms').'</option>
											<option '.(($other_config['set-wp-user-level']=='author') ? 	'selected="selected"' : '').' value="author">'.__('Author','nex-forms').'</option>
											<option '.(($other_config['set-wp-user-level']=='editor') ? 	'selected="selected"' : '').' value="editor">'.__('Editor','nex-forms').'</option>
											<option '.(($other_config['set-wp-user-level']=='administrator' || !$other_config['set-wp-user-level']) ? 	'selected="selected"' : '').' value="administrator">'.__('Administrator','nex-forms').'</option>			
										</select>
										
									</div>
								</div>
									
								<div class="row">
									<div class="col-sm-6">'.__('Admin Color Scheme','nex-forms').'</div>
									<div class="col-sm-6">
										
										
										<input type="radio" class="with-gap" name="enable-color-adapt" id="enable-color-adapt-light" value="2" '.(($user_config['enable-color-adapt']=='' || $user_config['enable-color-adapt']=='1' || $user_config['enable-color-adapt']=='2' || !isset($user_config['enable-color-adapt'])) ? 'checked="checked"' : '').'>
										<label for="enable-color-adapt-light">'.__('NEX-Forms Light','nex-forms').'</label><br />
										
										
										<input type="radio" class="with-gap" name="enable-color-adapt" id="enable-color-adapt-dark" value="3" '.(($user_config['enable-color-adapt']=='3' ) ? 'checked="checked"' : '').'>
										<label for="enable-color-adapt-dark">'.__('NEX-Forms Dark','nex-forms').'</label><br />
										
										<input type="radio" class="with-gap hidden" name="enable-color-adapt" id="enable-color-adapt-wp-admin" value="1" '.(($user_config['enable-color-adapt']=='1' || !$user_config['enable-color-adapt']) ? 'checked="checked"' : '').'>
										<label for="enable-color-adapt-wp-admin" class="hidden">'.__('WP Admin Color Scheme Adapt','nex-forms').'</label>
										
									
									
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-6">'.__('Enable Zero Conflict Admin','nex-forms').'</div>
									<div class="col-sm-6">
										
										
										
										
										<input type="radio" class="with-gap" name="zero-con" id="zero-con01" value="1" '.(($other_config['zero-con']=='1' || !$other_config['zero-con']) ? 	'checked="checked"' : '').'>
										<label for="zero-con01">'.__('Yes','nex-forms').'</label>
										
										
										<input type="radio" class="with-gap" name="zero-con" id="zero-con02" value="0" '.(($other_config['zero-con']=='0' ) ? 'checked="checked"' : '').'>
										<label for="zero-con02">'.__('No (Not Recomended)','nex-forms').'</label>
										
										
										
										
										
										
										
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-6">'.__('Enable NEX-Forms TinyMCE Button','nex-forms').'</div>
									<div class="col-sm-6">
										
										
										
										
										<input type="radio" class="with-gap" name="enable-tinymce" id="enable-tinymce01" value="1" '.(($other_config['enable-tinymce']=='1' || !$other_config['enable-tinymce']) ? 	'checked="checked"' : '').'>
										<label for="enable-tinymce01">'.__('Yes','nex-forms').'</label>
										
										
										<input type="radio" class="with-gap" name="enable-tinymce" id="enable-tinymce02" value="0" '.(($other_config['enable-tinymce']=='0' ) ? 'checked="checked"' : '').'>
										<label for="enable-tinymce02">'.__('No','nex-forms').'</label>
										
										
										
										
										
										
										
									</div>
								</div>
								
								<div class="row">
									<div class="col-sm-6">'.__('Enable NEX-Forms Widget','nex-forms').'</div>
									<div class="col-sm-6">
										
										
										
										<input type="radio" class="with-gap" name="enable-widget" id="enable-widget01" value="1" '.(($other_config['enable-widget']=='1' || !$other_config['enable-widget']) ? 	'checked="checked"' : '').'>
										<label for="enable-widget01">'.__('Yes','nex-forms').'</label>
										
										
										<input type="radio" class="with-gap" name="enable-widget" id="enable-widget02" value="0" '.(($other_config['enable-widget']=='0' ) ? 'checked="checked"' : '').'>
										<label for="enable-widget02">'.__('No','nex-forms').'</label>
										
										
										
										
										
										
									</div>
								</div>
						
						
							<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save WP Admin Options','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
							<div style="clear:both;"></div>';
						
									
										
					if($theme->Name!='NEX-Forms Demo')			
						$output .= '</form>';
					
					$output .= '</div>';
					
			$output .= '</div>';
		return $output;
	}
	
	
	
	public function troubleshooting_options(){
		
		$output = '';	
			$output .= '<div class="dashboard-box global_settings ">';
							$output .= '<div class="dashboard-box-header aa_bg_main">';
								$output .= '<div class="table_title"><i class="material-icons header-icon contact_mail">report_problem</i><span class="header_text ">'.__('Troubleshooting Options','nex-forms').'</span></div>';
								$output .= '
								<nav class="nav-extended dashboard_nav dashboard-box-nav">
									<div class="nav-content aa_bg_sec">
									  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu">
										<li class="tab"><a class="active" href="#js_inc">'.__('Javascript Includes','nex-forms').'</a></li>
										<li class="tab"><a href="#css_inc">'.__('Stylesheet Includes','nex-forms').'</a></li>
									  </ul>
									</div>
								 </nav>';
							$output .= '</div>';
							
							$output .= '<div  class="dashboard-box-content">';
								$output .= '<div id="js_inc" >';
									$output .= $this->print_js_inc();
								$output .= '</div>';
								
								$output .= '<div id="css_inc" style="display:none;">';
									$output .= $this->print_css_inc();
								$output .= '</div>';
								
							$output .= '</div>';
						$output .= '</div>';
		return $output;
	}
	
	public function print_js_inc(){
		$script_config = get_option('nex-forms-script-config');
		$theme = wp_get_theme();
		$output = '';
		$output .= '
				<form name="script_config" id="script_config" action="'.admin_url('admin-ajax.php').'" method="post">
					
					
					<div class="alert alert-info">'.__('Leave unchanged if you are not a developer with the proper know-how!','nex-forms').'</div>
					
					<div class="row">
											<div class="col-sm-4">'.__('WP Core javascript','nex-forms').'</div>
											<div class="col-sm-8">
												<input type="checkbox" '.(($script_config['inc-jquery']=='1') ? 	'checked="checked"' : '').' name="inc-jquery" value="1" 	id="inc-jquery"	><label for="inc-jquery">jQuery </label><br />
												<input type="checkbox" '.(($script_config['inc-jquery-ui-core']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-ui-core" value="1" 	id="inc-jquery-ui-core"	><label for="inc-jquery-ui-core">jQuery UI Core</label><br />
												<input type="checkbox" '.(($script_config['inc-jquery-ui-autocomplete']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-ui-autocomplete" value="1" 	id="inc-jquery-ui-autocomplete"	><label for="inc-jquery-ui-autocomplete">jQuery UI Autocomplete</label><br />
												<input type="checkbox" '.(($script_config['inc-jquery-ui-slider']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-ui-slider" value="1" 	id="inc-jquery-ui-slider"	><label for="inc-jquery-ui-slider">jQuery UI Slider</label><br />
												<input type="checkbox" '.(($script_config['inc-jquery-form']=='1') ? 	'checked="checked"' : '').' name="inc-jquery-form" value="1" 	id="inc-jquery-form"	><label for="inc-jquery-form">jQuery Form</label><br />
											</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">'.__('Extras','nex-forms').'</div>
												<div class="col-sm-8">
													
													<input type="checkbox" '.(($script_config['inc-wow']=='1') ? 	'checked="checked"' : '').' name="inc-wow" value="1" 	id="inc-wow"	><label for="inc-wow">Animations </label><br />
													
												
												</div>
											</div>
											
											<div class="row">
												<div class="col-sm-4">'.__('Plugin Dependent Javascript','nex-forms').'</div>
												<div class="col-sm-8">
													<input type="checkbox" '.(($script_config['inc-bootstrap']=='1') ? 	'checked="checked"' : '').' name="inc-bootstrap" value="1" 	id="inc-bootstrap"	><label for="inc-bootstrap">Bootstrap </label><br />
													<input type="checkbox" '.(($script_config['inc-onload']=='1') ? 	'checked="checked"' : '').' name="inc-onload" value="1" 	id="inc-onload"	><label for="inc-onload">Onload Functions </label><br />
												</div>
											</div>
											
											
											
					
					
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save JS Inclusions','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
					';
		
		return $output;
	}
	
	public function print_css_inc(){
		$styles_config = get_option('nex-forms-style-config');
		$output = '';
		$theme = wp_get_theme();
		$output .= '
				<form name="style_config" id="style_config" action="'.admin_url('admin-ajax.php').'" method="post">
					
					<div class="alert alert-info">'.__('Please leave these includes if you are not a developer who knows what you are doing!','nex-forms').'</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('WP Core stylesheets','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="checkbox" '.(($styles_config['incstyle-jquery']=='1') ? 	'checked="checked"' : '').' name="incstyle-jquery" value="1" 	id="incstyle-jquery"	> <label for="incstyle-jquery-ui">jQuery UI</label>	
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Other stylesheets','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="checkbox" '.(($styles_config['incstyle-bootstrap']=='1' || !array_key_exists('incstyle-bootstrap',$styles_config)) ? 	'checked="checked"' : '').' name="incstyle-bootstrap" value="1" 	id="incstyle-bootstrap"	><label for="incstyle-bootstrap">Bootstrap</label><br />
							<input type="checkbox" '.(($styles_config['incstyle-font-awesome']=='1' || !array_key_exists('incstyle-font-awesome',$styles_config)) ? 	'checked="checked"' : '').' name="incstyle-font-awesome" value="1" 	id="incstyle-font-awesome"	><label for="incstyle-font-awesome">Font Awesome</label><br />
							<input type="checkbox" '.(($styles_config['incstyle-font-awesome-v4-shims']=='1' || !array_key_exists('incstyle-font-awesome-v4-shims',$styles_config)) ? 	'checked="checked"' : '').' name="incstyle-font-awesome-v4-shims" value="1" 	id="incstyle-font-awesome-v4-shims"	><label for="incstyle-font-awesome-v4-shims">Font Awesome v4 Shims</label><br />
							<input type="checkbox" '.(($styles_config['incstyle-animations']=='1' || !array_key_exists('incstyle-animations',$styles_config)) ? 	'checked="checked"' : '').' name="incstyle-animations" value="1" 	id="incstyle-animations"	><label for="incstyle-animations">Animations</label><br />
							
							<input type="checkbox" '.(($styles_config['incstyle-custom']=='1') ? 	'checked="checked"' : '').' name="incstyle-custom" value="1" 	id="incstyle-custom"	><label for="incstyle-custom">Custom NEX-Forms CSS</label>
						</div>
					</div>
					
					
					
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save CSS Inclusions','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
					';
		
		return $output;
	}
	

	public function license_setup($args='', $client_info='', $license_info=''){
		
		
		
		
			$checked = $args;
		
		
		
		$output = '';
		//$fs = new Freemius();
		//$fs->_add_license_action_link();
				
		/*if ( function_exists( 'nf_fs' ) ) {
        $output = nf_fs()->_open_license_activation_dialog_box();
    }*/
	/*echo '<pre>';
	print_r($license_info);
	echo '</pre>';*/
		//$supported_until =  '2025-07-29T15:30:58+10:00';
		$supported_until = $license_info['supported_until'];
		$supported_date = new DateTime($supported_until);
		$now = new DateTime();
				
		$output .= '<div class="dashboard-box global_settings">';
			$output .= '<div class="dashboard-box-header aa_bg_main">';
				$output .= '<div class="table_title"><i class="material-icons header-icon">verified_user</i>'.__('NEX-Forms License Info','nex-forms').'</div>';
				$output .= '<p class="box-info"><strong>Status:</strong> '.(($checked=='true') ? '<span class="label label-success">'.__('Activated','nex-forms').'</span>' : '<span class="label label-danger">'.__('Not Activated','nex-forms').'</span>').'</p>';
			$output .= '</div>';
			
			$output .= '<div  class="dashboard-box-content activation_box">';
			
			if ( nf_fs()->can_use_premium_code() )
				$output .= 'License Active';	
			else
				{
				if(get_option('NFISENVA'))
					{
					if ($supported_date < $now)	
						{
						$output .= nf_fs()->_connect_page_render();		
						}
					}
				else
					{
					$output .= nf_fs()->_connect_page_render();	
					}
				}
			if(get_option('NFISENVA') && !nf_fs()->can_use_premium_code())
				{
				if($checked=='true')
					{	
					$theme = wp_get_theme();
					if($theme->Name=='NEX-Forms Demo')
						{
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Purchase Code','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= '&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;';
								$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Envato Username','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= 'Basix';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('License Type','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= 'Regular';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Activated on','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= 'Demo Site';
							$output .= '</div>';
						$output .= '</div>';
						
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-12">';
								$output .= '
								'.__('<div class="alert alert-info">Unregistering a Puchase Code will free up the above code to be re-used on another domain. <strong>NOTE:</strong> This will make the current active site\'s registration inactive!</div>
								<button class="btn blue waves-effect waves-light" disabled="disabled">Unregister Puchase Code</button>','nex-forms').'';
							$output .= '</div>';
						$output .= '</div>';
						}
					else
						{
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('License Type','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= '<span class="txt-green"><strong>Envato</strong></span> '.$client_info['license_type'];
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Purchase Code','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								if($client_info['purchase_code'])
									$output .= '&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;'.substr($client_info['purchase_code'],-6);
								else
									$output .= __('License not activated for this domain. Please refresh this page and enter your purchase code when prompted.','nex-forms');
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Envato Username','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= substr($client_info['envato_user_name'],0,1).'&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;'.substr($client_info['envato_user_name'],-1);
							$output .= '</div>';
						$output .= '</div>';
						
						$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Activated on','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= $client_info['for_site'];
							$output .= '</div>';
						$output .= '</div>';
						
						
						if ($supported_date < $now)
							$output .= '<div class="env_s_expired" style="display:none;">true</div>';
							
						if ($supported_date > $now) {
							$output .= '<div class="row">';
								$output .= '<div class="col-sm-12">';
									$output .= __('
									<div class="alert alert-info">Unregistering a Puchase Code will free up the above code to be re-registered on another domain. <strong>NOTE:</strong> This will make the current active site\'s registration inactive!</div>
									<button class="btn blue waves-effect waves-light deactivate_license">Unregister Puchase Code</button>','nex-forms');
								$output .= '</div>';
							$output .= '</div>';
							}
						else
							{
							
							
							
							$output .= '<div class="row">';
							$output .= '<div class="col-sm-5">';
								$output .= '<strong>'.__('Supported?','nex-forms').'</strong>';
							$output .= '</div>';
							$output .= '<div class="col-sm-7">';
								$output .= '<strong><span class="txt-red">Envato Support Expired!</span></strong>'; //substr($license_info['supported_until'], 0, 10)
							$output .= '</div>';
						$output .= '</div>';
							
							$output .= '<div class="upgrade_form_envato">
<h5>Your Envato support have expired, <strong>but...</strong></h5><h4>Great News!</h4> We\'ve launched a powerful new version of NEX-Forms on a cloud based platform from Freemius, and it\'s packed with benefits you won\'t want to miss:<br />
<br />

<span class="fa fa-check txt-green"></span>&nbsp;Ongoing Support & Updates<br />

<span class="fa fa-check txt-green"></span>&nbsp;Faster Performance & Better Stability<br />

<span class="fa fa-check txt-green"></span>&nbsp;Easy License Management<br />

<span class="fa fa-check txt-green"></span>&nbsp;Access to New Premium Features<br />

<span class="fa fa-check txt-green"></span>&nbsp;Access to Up and Coming Premium add-ons<br />

<span class="fa fa-check txt-green"></span>&nbsp;No More Support Renewal Hassles<br />

<h5>How do I upgrade?</h5>
All you have to do is this:
<br />
1. <a href="https://basixonline.net/nex-forms/pricing-comparison-envato-vs-SaaS/?promo=1&eu='.$client_info['envato_user_name'].'">Switch to a plan</a> that better suit your needs<br />
2. Activate your new license in the box below. That\'s it!
<br /><br />
<h5>Zero Loss</h5>
No forms, form entries, settings or any data from your NEX-Forms setup will be lost when you transition to the cloud based platform. All your forms, form submissions, settings and data will remain exactly as it is now.
<br /><br />
<h5>Added Bonus!</h5>
As an Envato License holder we will also offer you a <strong> 50% Discount</strong> on all pricing plans, simply click on the button below and claim your discount <span class="fa fa-arrow-down"></span><br />

<a href="https://basixonline.net/nex-forms/pricing-comparison-envato-vs-SaaS/?promo=1&eu='.$client_info['envato_user_name'].'" target="_blank" class="do_saas_upgrade">Upgrade NOW with a <strong>50% Discount</strong></a>
<a href="mailto:support@basixonline.net?subject=NEX-Forms Subscriptions Plan Question" target="_blank" class="do_saas_upgrade faq">I have more questions</a>
</div>
<div class="license_holder">Activate Cloud Based License:</div>
';	
							}
						}
					}
				else
					{
					$output .= __('<div class="alert alert-info">Currently, your NEX-Forms installation is not registered, which means some key features are disabled. To unlock these features and to gain FREE access to all premium add-ons you need to <a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock"?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=dashboard_activation" target="_blank"><strong>upgrade to the pro-version</strong></a></div>
					
								  <input name="purchase_code" id="purchase_code" placeholder="Enter Item Purchase Code" class="form-control" type="text">
								  <br />
								  <div class="show_code_response">
								  <div class="alert alert-success">After your <a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock"" target="_blank">purchase</a> you can find your purchase code from <a href="https://basixonline.net/nex-forms/pricing/?utm_source=wordpress_fs&utm_medium=upgrade&utm_content=feature_unlock" target="_blank"><strong>https://basixonline.net/nex-forms/pricing/</strong></a>. Click on Download next to NEX-Forms and then click on "License certificate &amp; purchase code" and copy that code into the above text field and hit Register.</div>
								  </div>
							   
							<button class="btn blue waves-effect waves-light deactivate_license hidden">Unregister Puchase Code</button>
							 <button class="btn blue waves-effect waves-light verify_purchase_code " type="button">Register</button> 
							<div style="clear:both"></div>
							','nex-forms');
					}
				}
		$output .= '</div>';	
	$output .= '</div>';	
			
		return $output;
	}
	
	public function preferences(){
		
		$output = '';	
		$output .= '<div class="dashboard-box global_settings field_preferences">';
							$output .= '<div class="dashboard-box-header aa_bg_main">';
								$output .= '<div class="table_title"><i class="material-icons header-icon">favorite</i><span class="header_text ">'.__('Presets / Overall Default Preferences','nex-forms').'</span></div>';
								$output .= '
								<nav class="nav-extended dashboard_nav dashboard-box-nav aa_bg_sec">
									<div class="nav-content aa_bg_sec">
									  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu">
									  <li class="tab"><a class="active" href="#email_pref">'.__('Email Presets','nex-forms').'</a></li>
									    <li class="tab"><a href="#other_pref">'.__('Form Presets','nex-forms').'</a></li>
										<li class="tab field_prefs"><a  href="#field_pref">'.__('Field Presets','nex-forms').'</a></li>
										<li class="tab"><a href="#validation_pref">'.__('Validation Preset Messages','nex-forms').'</a></li>
										
										
									  </ul>
									</div>
								 </nav>';
							$output .= '</div>';
							
							$output .= '<div  class="dashboard-box-content">';
								//FIELD PREFERENCES
								
								$output .= '<div id="email_pref" >';
									$output .= $this->print_email_pref();
								$output .= '</div>';
								
								$output .= '<div id="other_pref" style="display:none;">';
									$output .= $this->print_other_pref();
								$output .= '</div>';
								
								
								$output .= '<div id="field_pref" style="display:none;">';
									$output .= $this->print_field_pref();
								$output .= '</div>';
								
								$output .= '<div id="validation_pref" style="display:none;" >';
									$output .= $this->print_validation_pref();
								$output .= '</div>';
								
								
								
								
								
							$output .= '</div>';
		  			$output .= '</div>';
		return $output;
		}
		
		public function print_field_pref(){
			$preferences = get_option('nex-forms-preferences');
			$theme = wp_get_theme();
			
			$date_picker_lang = 'en';
			
			$date_picker_format = 'DD/MM/YYYY';
			
			if($preferences['field_preferences']['pref_date_picker_lang'])
				$date_picker_lang = $preferences['field_preferences']['pref_date_picker_lang'];
				
			if($preferences['field_preferences']['pref_date_picker_format'])
				$date_picker_format = $preferences['field_preferences']['pref_date_picker_format'];
			
			$output = '';
			$output .= '
			
			
			
				<form name="field-pref" id="field-pref" action="'.admin_url('admin-ajax.php').'" method="post">	
				
				<br /><div class="alert alert-info">'.__('NOTE: These presets does not affect already created forms and only takes effect on NEW forms!','nex-forms').'</div>
				
					<h5>Field Labels</h5>
						<div class="row">
							<div class="col-sm-4">'.__('Label Position','nex-forms').'</div>
							<div class="col-sm-8">
								
								<input type="radio" class="with-gap" name="pref_label_align" '.((!$preferences['field_preferences']['pref_label_align'] || $preferences['field_preferences']['pref_label_align']=='top') ? 'checked="checked"' : '').' id="pref_label_align_top" value="top">
								<label for="pref_label_align_top">'.__('Top','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_align" id="pref_label_align_left" value="left" '.(($preferences['field_preferences']['pref_label_align']=='left') ? 'checked="checked"' : '').'>
								<label for="pref_label_align_left">'.__('Left','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_align" id="pref_label_align_right" value="right" '.(($preferences['field_preferences']['pref_label_align']=='right') ? 'checked="checked"' : '').'>
								<label for="pref_label_align_right">'.__('Right','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_align" id="pref_label_align_hidden" value="hidden" '.(($preferences['field_preferences']['pref_label_align']=='hidden') ? 'checked="checked"' : '').'>
								<label for="pref_label_align_hidden">'.__('Hidden','nex-forms').'</label>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4">Label Text Alignment</div>
							<div class="col-sm-8">
								
								
								<input type="radio" class="with-gap" name="pref_label_text_align" id="pref_label_text_align_left" value="align_left" '.((!$preferences['field_preferences']['pref_label_text_align'] || $preferences['field_preferences']['pref_label_text_align']=='align_left' || $preferences['field_preferences']['pref_label_text_align']=='align_let') ? 'checked="checked"' : '').'> 
								<label for="pref_label_text_align_left">'.__('Left','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_text_align" id="pref_label_text_align_right" value="align_right" '.(($preferences['field_preferences']['pref_label_text_align']=='align_right') ? 'checked="checked"' : '').'> 
								<label for="pref_label_text_align_right">'.__('Right','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_text_align" id="pref_label_text_align_center" value="align_center" '.(($preferences['field_preferences']['pref_label_text_align']=='align_center') ? 'checked="checked"' : '').'> 
								<label for="pref_label_text_align_center">'.__('Center','nex-forms').'</label>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4">Label Size</div>
							<div class="col-sm-8">
								
								<input type="radio" class="with-gap" name="pref_label_size" id="pref_label_size_sm" value="text-sm" '.(($preferences['field_preferences']['pref_label_size']=='text-sm') ? 'checked="checked"' : '').'> 
								<label for="pref_label_size_sm">'.__('Small','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_size" id="pref_label_size_normal" value="" '.((!$preferences['field_preferences']['pref_label_size'] || $preferences['field_preferences']['pref_label_size']=='') ? 'checked="checked"' : '').'> 
								<label for="pref_label_size_normal">'.__('Normal','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_label_size"  id="pref_label_size_lg" value="text-lg" '.(($preferences['field_preferences']['pref_label_size']=='text-lg') ? 'checked="checked"' : '').'>
								<label for="pref_label_size_lg">'.__('Large','nex-forms').'</label>
							</div>
						</div>
						
						<div class="row">

							<div class="col-sm-4">Show Sublabel</div>
							<div class="col-sm-8">
								
								
								
								<input type="radio" class="with-gap" name="pref_sub_label"  id="pref_sub_label_01" value="on" '.(($preferences['field_preferences']['pref_sub_label']=='on') ? 'checked="checked"' : '').'>
								<label for="pref_sub_label_01">'.__('Yes','nex-forms').'</label>
								
								
								<input type="radio" class="with-gap" name="pref_sub_label"  id="pref_sub_label_02" value="off" '.(($preferences['field_preferences']['pref_sub_label']=='off' || !$preferences['field_preferences']['pref_sub_label']) ? 'checked="checked"' : '').'>
								<label for="pref_sub_label_02">'.__('No','nex-forms').'</label>
								
								
							</div>
						</div>
						
						
						
						<h5>Field Inputs</h5>

						<div class="row">
							<div class="col-sm-4">'.__('Input Text Alignment','nex-forms').'</div>
							<div class="col-sm-8">
								
								
								
								<input type="radio" class="with-gap" name="pref_input_text_align" id="pref_input_text_align_left" value="align_left" '.((!$preferences['field_preferences']['pref_input_text_align'] || $preferences['field_preferences']['pref_input_text_align']=='align_left' || $preferences['field_preferences']['pref_input_text_align']=='aling_left') ? 'checked="checked"' : '').'> 
								<label for="pref_input_text_align_left">'.__('Left','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_input_text_align" id="pref_input_text_align_right" value="align_right" '.(($preferences['field_preferences']['pref_input_text_align']=='align_right') ? 'checked="checked"' : '').'> 
								<label for="pref_input_text_align_right">'.__('Right','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_input_text_align" id="pref_input_text_align_center" value="align_center" '.(($preferences['field_preferences']['pref_input_text_align']=='align_center') ? 'checked="checked"' : '').'> 
								<label for="pref_input_text_align_center">'.__('Center','nex-forms').'</label>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4">'.__('Input Size','nex-forms').'</div>
							<div class="col-sm-8">
								
								<input type="radio" class="with-gap" name="pref_input_size" id="pref_input_size_sm" value="input-sm" '.(($preferences['field_preferences']['pref_input_size']=='input-sm') ? 'checked="checked"' : '').'> 
								<label for="pref_input_size_sm">'.__('Small','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_input_size" id="pref_input_size_normal" value="" '.((!$preferences['field_preferences']['pref_input_size'] || $preferences['field_preferences']['pref_input_size']=='') ? 'checked="checked"' : '').'> 
								<label for="pref_input_size_normal">'.__('Normal','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_input_size"  id="pref_input_size_lg" value="input-lg" '.(($preferences['field_preferences']['pref_input_size']=='input-lg') ? 'checked="checked"' : '').'> 
								<label for="pref_input_size_lg">'.__('Large','nex-forms').'</label>
							</div>
						</div>
						
						<div class="row">
						<div class="col-sm-4">'.__('Date Picker Format','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_date_picker_format" class="form-control" value="'.$date_picker_format.'"><small>See more about <a href="https://basixonline.net/nex-forms-docs/version-8/">date formatting syntax here</a></small>
						</div>
					</div>
						
						<div class="row">
							<div class="col-sm-4">'.__('Date Picker Language','nex-forms').'</div>
							<div class="col-sm-8">
								<select class="form-control pref_date_picker_lang" id="date-picker-lang-selector" name="pref_date_picker_lang" data-selected="'.$date_picker_lang.'">
								<option value="en">en</option><option value="ar-ma">ar-ma</option><option value="ar-sa">ar-sa</option><option value="ar-tn">ar-tn</option><option value="ar">ar</option><option value="bg">bg</option><option value="ca">ca</option><option value="cs">cs</option><option value="da">da</option><option value="de-at">de-at</option><option value="de">de</option><option value="el">el</option><option value="en-au">en-au</option><option value="en-ca">en-ca</option><option value="en-gb">en-gb</option><option value="es">es</option><option value="fa">fa</option><option value="fi">fi</option><option value="fr-ca">fr-ca</option><option value="fr">fr</option><option value="he">he</option><option value="hi">hi</option><option value="hr">hr</option><option value="hu">hu</option><option value="id">id</option><option value="is">is</option><option value="it">it</option><option value="ja">ja</option><option value="ko">ko</option><option value="lt">lt</option><option value="lv">lv</option><option value="nb">nb</option><option value="nl">nl</option><option value="pl">pl</option><option value="pt-br">pt-br</option><option value="pt">pt</option><option value="ro">ro</option><option value="ru">ru</option><option value="sk">sk</option><option value="sl">sl</option><option value="sr-cyrl">sr-cyrl</option><option value="sr">sr</option><option value="sv">sv</option><option value="th">th</option><option value="tr">tr</option><option value="uk">uk</option><option value="vi">vi</option><option value="zh-cn">zh-cn</option><option value="zh-tw">zh-tw</option></select>
							</div>
						</div>
						
						<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save Field Preferences','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
						<div style="clear:both"></div>
					</form>
					';
		return $output;	
		}
		
		
		public function print_validation_pref(){
			$theme = wp_get_theme();
			$preferences = get_option('nex-forms-preferences');
			$output = '';
			$output .= '
				<form name="validation-pref" id="validation-pref" action="'.admin_url('admin-ajax.php').'" method="post">	
					
					<div class="alert alert-info">'.__('NOTE: These presets does not affect already created forms and only takes effect on NEW forms!','nex-forms').'</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Required Field','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_requered_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_requered_msg']) ? $preferences['validation_preferences']['pref_requered_msg'] : 'Required').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Incorect Email','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_email_format_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_email_format_msg']) ? $preferences['validation_preferences']['pref_email_format_msg'] : 'Invalid email address').'">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">'.__('Incorect Phone Number','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_phone_format_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_phone_format_msg']) ? $preferences['validation_preferences']['pref_phone_format_msg'] : 'Invalid phone number').'">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">'.__('Incorect URL','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_url_format_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_url_format_msg']) ? $preferences['validation_preferences']['pref_url_format_msg'] : 'Invalid URL').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Numerical','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_numbers_format_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_numbers_format_msg']) ? $preferences['validation_preferences']['pref_numbers_format_msg'] : 'Only numbers are allowed').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Alphabetical','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_char_format_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_char_format_msg']) ? $preferences['validation_preferences']['pref_char_format_msg'] : 'Only text are allowed').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Incorect File Extension','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_invalid_file_ext_msg" class="form-control" value="'.(($preferences['validation_preferences']['pref_invalid_file_ext_msg']) ? $preferences['validation_preferences']['pref_invalid_file_ext_msg'] : 'Invalid file extension').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Maximum File Size Exceeded','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_max_file_exceded" class="form-control" value="'.(($preferences['validation_preferences']['pref_max_file_exceded']) ? $preferences['validation_preferences']['pref_max_file_exceded'] : 'Maximum File Size of {x}MB Exceeded').'">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">'.__('Minimum File Size Required','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_min_file_exceded" class="form-control" value="'.(($preferences['validation_preferences']['pref_min_file_exceded']) ? $preferences['validation_preferences']['pref_min_file_exceded'] : 'Minimum File Size of {x}MB Required').'">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">'.__('Maximum Size for All Files Exceeded','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_max_file_af_exceded" class="form-control" value="'.(($preferences['validation_preferences']['pref_max_file_af_exceded']) ? $preferences['validation_preferences']['pref_max_file_af_exceded'] : 'Maximum Size for all files can not exceed {x}MB').'">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">'.__('Maximum File Upload Limit Exceeded','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="text" name="pref_max_file_ul_exceded" class="form-control" value="'.(($preferences['validation_preferences']['pref_max_file_ul_exceded']) ? $preferences['validation_preferences']['pref_max_file_ul_exceded'] : 'Only a maximum of {x} files can be uploaded').'">
						</div>
					</div>	
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save Validation Preferences','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
				';
			
		return $output;	
		}
		
		public function print_email_pref(){
			$preferences = get_option('nex-forms-preferences');
			$output = '';
			$theme = wp_get_theme();
			$output .= '
				<form name="emails-pref" id="emails-pref" action="'.admin_url('admin-ajax.php').'" method="post">	
					
					<br /><div class="alert alert-info">'.__('NOTE: These presets does not affect already created forms and only takes effect on NEW forms!','nex-forms').'</div>
					
					<h5>'.__('Admin Email Presets','nex-forms').'</h5>
															
															<div class="row">
																<div class="col-sm-4">'.__('From Address','nex-forms').'</div>
																<div class="col-sm-8">
																	<input type="text" name="pref_email_from_address" class="form-control" value="'.(($preferences['email_preferences']['pref_email_from_address']) ? $preferences['email_preferences']['pref_email_from_address'] : get_option('admin_email')).'">
																</div>
															</div>
															
															<div class="row">
																<div class="col-sm-4">'.__('From Name','nex-forms').'</div>
																<div class="col-sm-8">
																	<input type="text" name="pref_email_from_name" class="form-control" value="'.(($preferences['email_preferences']['pref_email_from_name']) ? $preferences['email_preferences']['pref_email_from_name'] : get_option('blogname')).'">
																</div>
															</div>
															
															<div class="row">
																<div class="col-sm-4">'.__('Recipients','nex-forms').'</div>
																<div class="col-sm-8">
																	<input type="text" name="pref_email_recipients" class="form-control" value="'.(($preferences['email_preferences']['pref_email_recipients']) ? $preferences['email_preferences']['pref_email_recipients'] : get_option('admin_email')).'">
																</div>
															</div>
															
															<div class="row">
																<div class="col-sm-4">'.__('Subject','nex-forms').'</div>
																<div class="col-sm-8">
																	<input type="text" name="pref_email_subject" class="form-control" value="'.(($preferences['email_preferences']['pref_email_subject']) ? $preferences['email_preferences']['pref_email_subject'] : get_option('blogname').' NEX-Forms submission').'">
																</div>
															</div>
															
															<div class="row">
																<div class="col-sm-4">'.__('Mail Body','nex-forms').'</div>
																<div class="col-sm-8">
																	<textarea name="pref_email_body" placeholder="'.__('Enter {{nf_form_data}} to display all submitted data from the form in a table','nex-forms').'" class="form-control">'.(($preferences['email_preferences']['pref_email_body']) ? $preferences['email_preferences']['pref_email_body'] : '{{nf_form_data}}').'</textarea>
																</div>
															</div>
															
															<h5>'.__('User Autoresponder Email Presets','nex-forms').'</h5>
															
															
															
															<div class="row">
																<div class="col-sm-4">'.__('Subject','nex-forms').'</div>
																<div class="col-sm-8">
																	<input type="text" name="pref_user_email_subject" class="form-control" value="'.(($preferences['email_preferences']['pref_user_email_subject']) ? $preferences['email_preferences']['pref_user_email_subject'] : get_option('blogname').' NEX-Forms submission').'">
																</div>
															</div>
															
															<div class="row">
																<div class="col-sm-4">'.__('Mail Body','nex-forms').'</div>
																<div class="col-sm-8">
																	<textarea name="pref_user_email_body" placeholder="'.__('Enter {{nf_form_data}} to display all submitted data from the form in a table','nex-forms').'" class="form-control">'.(($preferences['email_preferences']['pref_user_email_body']) ? $preferences['email_preferences']['pref_user_email_body'] : 'Thank you for connecting with us. We will respond to you shortly.').'</textarea>
																</div>
															</div>
					
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save Email Preferences','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
				';
			
		return $output;	
		}
		
		public function print_other_pref(){
			$preferences = get_option('nex-forms-preferences');
			$output = '';
			$theme = wp_get_theme();
			$output .= '
				<form name="other-pref" id="other-pref" action="'.admin_url('admin-ajax.php').'" method="post">	
					<div class="alert alert-info">'.__('NOTE: These presets does not affect already created forms and only takes effect on NEW forms!','nex-forms').'</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Form Background','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="color" name="pref_form_bg" class="form-control" value="'.(($preferences['other_preferences']['pref_form_bg']) ? $preferences['other_preferences']['pref_form_bg'] : '#FFFFFF').'">
						</div>
					</div>
					
					<div class="row">
							<div class="col-sm-4">'.__('Form Shadow','nex-forms').'</div>
							<div class="col-sm-8">
								
								<input type="radio" class="with-gap" name="pref_form_shadow" '.((!$preferences['other_preferences']['pref_form_shadow'] || $preferences['other_preferences']['pref_form_shadow']=='light') ? 'checked="checked"' : '').' id="pref_form_shadow_light" value="light">
								<label for="pref_form_shadow_light">'.__('Light','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_form_shadow" id="pref_form_shadow_dark" value="dark" '.(($preferences['other_preferences']['pref_form_shadow']=='dark') ? 'checked="checked"' : '').'>
								<label for="pref_form_shadow_dark">'.__('Dark','nex-forms').'</label>
								
								<input type="radio" class="with-gap" name="pref_form_shadow" id="pref_form_shadow_none" value="none" '.(($preferences['other_preferences']['pref_form_shadow']=='none') ? 'checked="checked"' : '').'>
								<label for="pref_form_shadow_none">'.__('None','nex-forms').'</label>
								
							</div>
						</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Form Padding','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="number" name="pref_form_padding" class="form-control" value="'.(($preferences['other_preferences']['pref_form_padding']) ? $preferences['other_preferences']['pref_form_padding'] : '30').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('Form Border Radius','nex-forms').'</div>
						<div class="col-sm-8">
							<input type="number" name="pref_form_border_radius" class="form-control" value="'.(($preferences['other_preferences']['pref_form_border_radius']) ? $preferences['other_preferences']['pref_form_border_radius'] : '4').'">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">'.__('On-screen confirmation message','nex-forms').'</div>
						<div class="col-sm-8">
							<textarea name="pref_other_on_screen_message" class="form-control">'.(($preferences['other_preferences']['pref_other_on_screen_message']) ? $preferences['other_preferences']['pref_other_on_screen_message'] : 'Thank you for connecting with us. We will respond to you shortly.').'</textarea>
						</div>
					</div>
						
					<button class="btn blue waves-effect waves-light" '.(($theme->Name=='NEX-Forms Demo') ? 'disabled="disabled"' : '').'>&nbsp;&nbsp;&nbsp;'.__('Save Form Preferences','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
					<div style="clear:both"></div>
				</form>
				';
			
		return $output;	
		}
	
	
	
	}	
}

$get_nf_dashboard = new NEXForms_dashboard();

?>