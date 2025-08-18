<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('NEXForms_Functions'))
	{
	class NEXForms_Functions{
	
	public function __construct(){
			
			add_action('wp_ajax_do_upload_image', array($this,'do_upload_image'));
			if ( function_exists( 'activator_inject_plugins_filter' ) ) {
				 return false;
			 }
	}
	
	public function new_form_setup($args=''){

		$output = '';
		
		
		
		$output .= '<div id="new_form_setup" class="modal animated fadeInDown">';
			//HEADER 
			//$theme = wp_get_theme();
			
			$output .= '<div class="modal-header aa_bg_main">';
				$output .= '<div class="modal-close back-to-dash"><span class="fas fa-arrow-left"></span></div><h4>'.__('Create a new form','nex-forms').' - <div class="sub-heading">'.__('Create a new Blank Form','nex-forms').'</div></h4>';
				$output .= '<i class="modal-action modal-close"><i class="fa fa-close"></i></i>';
			$output .= '</div>';
			//CONTENT
			$output .= '<div class="modal-content">';
				$output .= '<div class="new-form-sidebar aa_bg_sec aa_menu">';
					$output .= '<ul>';
						
							$output .= '<li class="active">	<a class="" data-panel="panel-1" data-sub-heading="'.__('Create a new Blank Form','nex-forms').'"><span class="fas fa-file"></span> '.__('Blank','nex-forms').'</a></li>';
							$output .= '<li>				<a class="" data-panel="panel-2" data-sub-heading="'.__('Form Templates','nex-forms').'"><span class="fas fa-file-invoice"></span> '.__('Templates','nex-forms').'</a></li>';
							
							
								$output .= '<li>				<a class="" data-panel="panel-3" data-sub-heading="'.__('Tutorials','nex-forms').'"><span class="fas fa-graduation-cap"></span> '.__('Tutorials','nex-forms').'</a></li>';
							
							$output .= '<li>				<a class="" data-panel="panel-4" data-sub-heading="'.__('Import Form','nex-forms').'"><span class="fas fa-file-upload"></span> '.__('Import','nex-forms').'</a></li>';
						
						
						
						//if($theme->Name!='NEX-Forms Demo')
							//$output .= '<li>				<a class="" data-panel="panel-5" data-sub-heading="'.__('Manual Form Import','nex-forms').'"><span class="fas fa-file-import"></span> '.__('Manual Import','nex-forms').'</a></li>';
					$output .= '<ul>';
				$output .= '</div>';
				
				//BLANK
				$output .= '<div class="new-form-panel ajax_loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
				$output .= '<div class="new-form-panel ajax_error_response">';
				$output .= '<div class="alert alert-danger">'.__('Sorry, something went wrong while reading the import file. Please try MANUAL IMPORT instead.','nex-forms').'</div>';
				$output .= '</div>';
				$output .= '<div class="new-form-panel panel-1 active">';
					$output .= '<div class="row">';
						$output .= '<div class="col-sm-12">';
							$output .= '<div class="dashboard-box database_table wap_nex_forms">
							
							<div class="dashboard-box-header aa_bg_main"><div class="table_title font_color_1 ">Create a new Blank Form</div></div>
							<div class="dashboard-box-content ">
							';
							$output .= '<form class="new_nex_form" name="new_nex_form" id="new_nex_form" method="post" action="'.admin_url('admin-ajax.php').'">';
						
								//$output .= '<h5><strong>'.__('Create a new Blank Form','nex-forms').'</strong></h5>';
								
								$nonce_url = wp_create_nonce( 'nf_admin_new_form_actions' );
		 						$output .= '<input name="nex_forms_wpnonce" type="hidden" value="'.$nonce_url.'">';
								
								$output .= '<input name="title" id="form_title" placeholder="'.__('Enter new Form Title','nex-forms').'" class="form-control" type="text">';		
						
								$output .= '<button type="submit" class="form-control submit_new_form btn blue waves-effect waves-light">'.__('Create','nex-forms').'</button>';
							
							$output .= '</form>';
							$output .= '</div></div>';
						$output .= '</div>';
						
						/*$output .= '<div class="col-sm-2"></div>';*/
					$output .= '</div>';
					
					
				$output .= '</div>';
				
				$output .= '<div class="new-form-panel panel-2">';
					//$output .= '<h5><strong>'.__('Form Templates','nex-forms').'</strong></h5>';
					//$output .= '<p>'.__('Select any of the pre-made form demo templates below to quick start your form. ','nex-forms').'</p>';
					$output .= '<div class="row">';
					$output .= '<div class="col-sm-12">';
					if(!$args)
						$output .= '<div class="alert alert-danger" style="width:95%"><span class="fas fa-lock"></span> PREMIUM ONLY FEATURE: An active premium license is required to gain access to pre-made templates. <a href="https://basixonline.net/nex-forms/pricing/" class="upgrade-link" target="_blank"> Upgrade to Premium <span class="fa-solid fa-angles-up"></span></a></div>';	
					else
						{
						foreach ( scandir( plugin_dir_path( dirname(dirname(__FILE__)))  . "includes/templates/" ) as $dir )
							{
							if($dir != '.' && $dir != '..' && $dir != 'Tutorials' && $dir != 'index.php' )
								{
								$get_category = explode('-',$dir);
								$output .= '
								<div class="dashboard-box database_table">
								<div class="dashboard-box-header aa_bg_main"><div class="table_title font_color_1 ">'.$get_category[1].'</div></div>
								';
								$output .= '<div class="template-box-content "><div class="row">';
								foreach ( glob( plugin_dir_path( dirname(dirname(__FILE__)))  . "includes/templates/".$dir."/*.txt" ) as $file )
									{
									$get_file_name = explode($dir.'/',$file);
									$get_file_name = $get_file_name[1];
									$get_file_name = str_replace('.txt','',$get_file_name);
									$get_file_name = str_replace(' ','%20',$get_file_name);
									
									$set_template_title = explode('-',$get_file_name);
									
									if(!strstr('Tutorial', $get_file_name))
										{
										$output .= '<div class="col-sm-3">';
											$output .= '<a class="template_box new_form_option load_template" data-nex-step="creating_new_form" data-template-name="'.$get_file_name.'" data-template-dir="'.$dir.'">';
											$output .= '<div class="img"><img src="https://basixonline.net/demo_templates/images_v7.6.5/'.$this->format_name($get_file_name).'.jpg"></div>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
											$output .= '<div class="description">'.str_replace('%20',' ',$set_template_title[1]).'</div></a>';
										$output .= '</div>';
										}
									}
								$output .= '</div></div>';
								$output .= '</div>';
								}
							}	
							
						/*foreach ( glob( plugin_dir_path( dirname(dirname(__FILE__)))  . "templates/*.txt" ) as $file )
							{
							$get_file_name = explode('templates/',$file);
							$get_file_name = $get_file_name[1];
							$get_file_name = str_replace('.txt','',$get_file_name);
							$get_file_name = str_replace(' ','%20',$get_file_name);
							if(!strstr('Tutorial', $get_file_name))
								{
								$output .= '<div class="col-sm-3">';
									$output .= '<a class="template_box new_form_option load_template" data-nex-step="creating_new_form" data-template-name="'.$get_file_name.'">';
									$output .= '<div class="img"><img src="https://basixonline.net/demo_templates/images/'.$this->format_name($get_file_name).'.jpg"></div>';
									$output .= '<div class="description">'.str_replace('%20',' ',$get_file_name).'</div></a>';
								$output .= '</div>';
								}
							
						}*/
					}	
						$output .= '</div>';
						$output .= '</div>';
				$output .= '</div>';
				
				$output .= '<div class="new-form-panel panel-3">';
				
					//$output .= '<h5><strong>'.__('Tutorials','nex-forms').'</strong></h5>';
					
					$output .= '<div class="row">';
						$output .= '<div class="tut_wrapper load_template" data-nex-step="creating_new_form" data-template-name="Tutorial - 1" data-template-dir="Tutorials" data-is-tut="true" data-tut-num="1">';
								$output .= '<div class="col-xs-12">';
									$output .= '<div class="col-xs-2 tut_icon">';
										$output .= '<div class="icon"><span class="fa fa-wpbeginner"></span></div>';
									$output .= '</div>';
									
									$output .= '<div class="col-xs-8 tut_description">';
										$output .= '<div class="header">'.__('Tutorial 1','nex-forms').'</div>';
										$output .= '<div class="sub-header">'.__('Creating a form','nex-forms').'</div>';
										$output .= '<div class="description">'.__('Learn the basics of the builder by creating a simple contact form','nex-forms').'</div>';
									$output .= '</div>';
									
									
									//$output .= '<div class="col-xs-2 tut_status">';
									//	$output .= '<div class="status incomplete">Incomplete<span class="fas fa-times-circle"></span></div>';
									//$output .= '</div>';
									
								$output .= '</div>';
							$output .= '</div>';
							
						$output .= '</div>';
						
					
					$output .= '<div class="row">';
						$output .= '<div class="tut_wrapper load_template" data-nex-step="creating_new_form" data-template-name="Tutorial - 2" data-template-dir="Tutorials" data-is-tut="true" data-tut-num="2">';
								$output .= '<div class="col-xs-12">';
									$output .= '<div class="col-xs-2 tut_icon">';
										$output .= '<div class="icon"><span class="fa fa-random"></span></div>';
									$output .= '</div>';
									
									$output .= '<div class="col-xs-8 tut_description">';
										$output .= '<div class="header">'.__('Tutorial 2','nex-forms').'</div>';
										$output .= '<div class="sub-header">'.__('Using Conditional Logic','nex-forms').'</div>';
										$output .= '<div class="description">'.__('Learn how to use Conditional Logic','nex-forms').'</div>';
									$output .= '</div>';
									
									
									//$output .= '<div class="col-xs-2 tut_status">';
									//	$output .= '<div class="status incomplete">Incomplete<span class="fas fa-times-circle"></span></div>';
									//$output .= '</div>';
									
								$output .= '</div>';
							$output .= '</div>';
							
						$output .= '</div>';
					
					
					
					$output .= '<div class="row">';
						$output .= '<div class="tut_wrapper load_template" data-nex-step="creating_new_form" data-template-name="Tutorial - 3" data-template-dir="Tutorials" data-is-tut="true" data-tut-num="3">';
								$output .= '<div class="col-xs-12">';
									$output .= '<div class="col-xs-2 tut_icon">';
										$output .= '<div class="icon"><span class="fa fa-calculator"></span></div>';
									$output .= '</div>';
									
									$output .= '<div class="col-xs-8 tut_description">';
										$output .= '<div class="header">'.__('Tutorial 3','nex-forms').'</div>';
										$output .= '<div class="sub-header">'.__('Using Math Logic','nex-forms').'</div>';
										$output .= '<div class="description">'.__('Learn how to setup math equations in your forms for live calculations.','nex-forms').'</div>';
									$output .= '</div>';
									
									
									//$output .= '<div class="col-xs-2 tut_status">';
									//	$output .= '<div class="status incomplete">Incomplete<span class="fas fa-times-circle"></span></div>';
									//$output .= '</div>';
									
								$output .= '</div>';
							$output .= '</div>';
							
						$output .= '</div>';
					
					
					$output .= '<div class="row">';
						$output .= '<div class="tut_wrapper load_template" data-nex-step="creating_new_form" data-template-name="Tutorial - 4" data-template-dir="Tutorials" data-is-tut="true" data-tut-num="4">';
								$output .= '<div class="col-xs-12">';
									$output .= '<div class="col-xs-2 tut_icon">';
										$output .= '<div class="icon"><span class="fa fa-copy"></span></div>';
									$output .= '</div>';
									
									$output .= '<div class="col-xs-8 tut_description">';
										$output .= '<div class="header">'.__('Tutorial 4','nex-forms').'</div>';
										$output .= '<div class="sub-header">'.__('Creating Multi-Steps','nex-forms').'</div>';
										$output .= '<div class="description">'.__('Learn how to create multi-step forms.','nex-forms').'</div>';
									$output .= '</div>';
									
									
									//$output .= '<div class="col-xs-2 tut_status">';
									//	$output .= '<div class="status incomplete">Incomplete<span class="fas fa-times-circle"></span></div>';
									//$output .= '</div>';
									
								$output .= '</div>';
							$output .= '</div>';
							
						$output .= '</div>';
					
						
				$output .= '</div>';
				
				$output .= '<div class="new-form-panel panel-4">';
				
					//$output .= '<h5><strong>'.__('Import Form','nex-forms').'</strong></h5>';
					$output .= '<p>'.__('Browse to any form exported by NEX-Forms. Open it to start import.','nex-forms').'</p>';
					if(!$args)
						$output .= '<div class="alert alert-danger" style="width:95%"><span class="fas fa-lock"></span> PREMIUM ONLY FEATURE: An active premium license is required import forms. <a href="https://basixonline.net/nex-forms/pricing/" class="upgrade-link" target="_blank"> Upgrade to Premium <span class="fa-solid fa-angles-up"></span></a></div>';	
					else
						{
						$output .= '<button id="upload_form" class="form-control  btn blue waves-effect waves-light import_form">'.__('Import Form','nex-forms').'</button>';
						}
				$output .= '</div>';
				
				$output .= '<div class="new-form-panel panel-5">';
					
					$output .= '<div class="row">';
						$output .= '<div class="col-sm-12">';
							if(!$args)
								$output .= '<div class="alert alert-danger" style="width:95%"><strong>'.__('Plugin not registered. Please register the plugin to enable form imports.','nex-forms').'</strong></div>';	
							else
								{
								$output .= '<form class="manual_import_form" name="manual_import_form" id="manual_import_form" method="post" action="'.admin_url('admin-ajax.php').'">';
							
									//$output .= '<h5><strong>'.__('Manual Form Import','nex-forms').'</strong></h5>';
									
									$output .= '<p>'.__('1. Open your exported .txt form file in a normal text editor like MS Notepad','nex-forms').'</p>';
									$output .= '<p>'.__('2. Copy all the content in the file','nex-forms').'</p>';
									$output .= '<p>'.__('3. Past the copied content here in the Textarea below and hit Import','nex-forms').'</p>';
									
									$output .= '<textarea name="form_content" id="form_content" placeholder="Paste exported form data here..." class="form-control"></textarea>';		
							
									$output .= '<button type="submit" class="form-control submit_new_form btn blue waves-effect waves-light">'.__('Import','nex-forms').'</button>';
								
								$output .= '</form>';
								}
							
						$output .= '</div>';
					$output .= '</div>';
					
				$output .= '</div>';
				
			$output .= '</div> ';
			
		$output .= '</div>';
			
			
			
			
			
			
			
			
			$output .= '
					<form name="import_form" class="hidden" id="import_form" action="'.admin_url('admin-ajax.php').'" enctype="multipart/form-data" method="post">	
						<input type="file" name="form_html">
						<div class="row">
							<div class="modal-footer">
								<button class="btn btn-default">&nbsp;&nbsp;&nbsp;'.__('Save Settings','nex-forms').'&nbsp;&nbsp;&nbsp;</button>
							</div>
						</div>
							
					</form>
					';				  
				
			return $output;
		
	}
	
	public function code_to_country_flag($code) 
		{
		//return '';
		$emoji_flags = array();

		// Now, all the country flags as emojis
		$emoji_flags["AD"] = "&#127462;&#127465;";
$emoji_flags["AE"] = "&#127462;&#127466;";
$emoji_flags["AF"] = "&#127462;&#127467;";
$emoji_flags["AG"] = "&#127462;&#127468;";
$emoji_flags["AI"] = "&#127462;&#127470;";
$emoji_flags["AL"] = "&#127462;&#127473;";
$emoji_flags["AM"] = "&#127462;&#127474;";
$emoji_flags["AO"] = "&#127462;&#127476;";
$emoji_flags["AQ"] = "&#127462;&#127478;";
$emoji_flags["AR"] = "&#127462;&#127479;";
$emoji_flags["AS"] = "&#127462;&#127480;";
$emoji_flags["AT"] = "&#127462;&#127481;";
$emoji_flags["AU"] = "&#127462;&#127482;";
$emoji_flags["AW"] = "&#127462;&#127484;";
$emoji_flags["AX"] = "&#127462;&#127485;";
$emoji_flags["AZ"] = "&#127462;&#127487;";
$emoji_flags["BA"] = "&#127463;&#127462;";
$emoji_flags["BB"] = "&#127463;&#127463;";
$emoji_flags["BD"] = "&#127463;&#127465;";
$emoji_flags["BE"] = "&#127463;&#127466;";
$emoji_flags["BF"] = "&#127463;&#127467;";
$emoji_flags["BG"] = "&#127463;&#127468;";
$emoji_flags["BH"] = "&#127463;&#127469;";
$emoji_flags["BI"] = "&#127463;&#127470;";
$emoji_flags["BJ"] = "&#127463;&#127471;";
$emoji_flags["BL"] = "&#127463;&#127473;";
$emoji_flags["BM"] = "&#127463;&#127474;";
$emoji_flags["BN"] = "&#127463;&#127475;";
$emoji_flags["BO"] = "&#127463;&#127476;";
$emoji_flags["BQ"] = "&#127463;&#127478;";
$emoji_flags["BR"] = "&#127463;&#127479;";
$emoji_flags["BS"] = "&#127463;&#127480;";
$emoji_flags["BT"] = "&#127463;&#127481;";
$emoji_flags["BV"] = "&#127463;&#127483;";
$emoji_flags["BW"] = "&#127463;&#127484;";
$emoji_flags["BY"] = "&#127463;&#127486;";
$emoji_flags["BZ"] = "&#127463;&#127487;";
$emoji_flags["CA"] = "&#127464;&#127462;";
$emoji_flags["CC"] = "&#127464;&#127464;";
$emoji_flags["CD"] = "&#127464;&#127465;";
$emoji_flags["CF"] = "&#127464;&#127467;";
$emoji_flags["CG"] = "&#127464;&#127468;";
$emoji_flags["CH"] = "&#127464;&#127469;";
$emoji_flags["CI"] = "&#127464;&#127470;";
$emoji_flags["CK"] = "&#127464;&#127472;";
$emoji_flags["CL"] = "&#127464;&#127473;";
$emoji_flags["CM"] = "&#127464;&#127474;";
$emoji_flags["CN"] = "&#127464;&#127475;";
$emoji_flags["CO"] = "&#127464;&#127476;";
$emoji_flags["CR"] = "&#127464;&#127479;";
$emoji_flags["CU"] = "&#127464;&#127482;";
$emoji_flags["CV"] = "&#127464;&#127483;";
$emoji_flags["CW"] = "&#127464;&#127484;";
$emoji_flags["CX"] = "&#127464;&#127485;";
$emoji_flags["CY"] = "&#127464;&#127486;";
$emoji_flags["CZ"] = "&#127464;&#127487;";
$emoji_flags["DE"] = "&#127465;&#127466;";
$emoji_flags["DG"] = "&#127465;&#127468;";
$emoji_flags["DJ"] = "&#127465;&#127471;";
$emoji_flags["DK"] = "&#127465;&#127472;";
$emoji_flags["DM"] = "&#127465;&#127474;";
$emoji_flags["DO"] = "&#127465;&#127476;";
$emoji_flags["DZ"] = "&#127465;&#127487;";
$emoji_flags["EC"] = "&#127466;&#127464;";
$emoji_flags["EE"] = "&#127466;&#127466;";
$emoji_flags["EG"] = "&#127466;&#127468;";
$emoji_flags["EH"] = "&#127466;&#127469;";
$emoji_flags["ER"] = "&#127466;&#127479;";
$emoji_flags["ES"] = "&#127466;&#127480;";
$emoji_flags["ET"] = "&#127466;&#127481;";
$emoji_flags["FI"] = "&#127467;&#127470;";
$emoji_flags["FJ"] = "&#127467;&#127471;";
$emoji_flags["FK"] = "&#127467;&#127472;";
$emoji_flags["FM"] = "&#127467;&#127474;";
$emoji_flags["FO"] = "&#127467;&#127476;";
$emoji_flags["FR"] = "&#127467;&#127479;";
$emoji_flags["GA"] = "&#127468;&#127462;";
$emoji_flags["GB"] = "&#127468;&#127463;";
$emoji_flags["GD"] = "&#127468;&#127465;";
$emoji_flags["GE"] = "&#127468;&#127466;";
$emoji_flags["GF"] = "&#127468;&#127467;";
$emoji_flags["GG"] = "&#127468;&#127468;";
$emoji_flags["GH"] = "&#127468;&#127469;";
$emoji_flags["GI"] = "&#127468;&#127470;";
$emoji_flags["GL"] = "&#127468;&#127473;";
$emoji_flags["GM"] = "&#127468;&#127474;";
$emoji_flags["GN"] = "&#127468;&#127475;";
$emoji_flags["GP"] = "&#127468;&#127477;";
$emoji_flags["GQ"] = "&#127468;&#127478;";
$emoji_flags["GR"] = "&#127468;&#127479;";
$emoji_flags["GS"] = "&#127468;&#127480;";
$emoji_flags["GT"] = "&#127468;&#127481;";
$emoji_flags["GU"] = "&#127468;&#127482;";
$emoji_flags["GW"] = "&#127468;&#127484;";
$emoji_flags["GY"] = "&#127468;&#127486;";
$emoji_flags["HK"] = "&#127469;&#127472;";
$emoji_flags["HM"] = "&#127469;&#127474;";
$emoji_flags["HN"] = "&#127469;&#127475;";
$emoji_flags["HR"] = "&#127469;&#127479;";
$emoji_flags["HT"] = "&#127469;&#127481;";
$emoji_flags["HU"] = "&#127469;&#127482;";
$emoji_flags["ID"] = "&#127470;&#127465;";
$emoji_flags["IE"] = "&#127470;&#127466;";
$emoji_flags["IL"] = "&#127470;&#127473;";
$emoji_flags["IM"] = "&#127470;&#127474;";
$emoji_flags["IN"] = "&#127470;&#127475;";
$emoji_flags["IO"] = "&#127470;&#127476;";
$emoji_flags["IQ"] = "&#127470;&#127478;";
$emoji_flags["IR"] = "&#127470;&#127479;";
$emoji_flags["IS"] = "&#127470;&#127480;";
$emoji_flags["IT"] = "&#127470;&#127481;";
$emoji_flags["JE"] = "&#127471;&#127466;";
$emoji_flags["JM"] = "&#127471;&#127474;";
$emoji_flags["JO"] = "&#127471;&#127476;";
$emoji_flags["JP"] = "&#127471;&#127477;";
$emoji_flags["KE"] = "&#127472;&#127466;";
$emoji_flags["KG"] = "&#127472;&#127468;";
$emoji_flags["KH"] = "&#127472;&#127469;";
$emoji_flags["KI"] = "&#127472;&#127470;";
$emoji_flags["KM"] = "&#127472;&#127474;";
$emoji_flags["KN"] = "&#127472;&#127475;";
$emoji_flags["KP"] = "&#127472;&#127477;";
$emoji_flags["KR"] = "&#127472;&#127479;";
$emoji_flags["KW"] = "&#127472;&#127484;";
$emoji_flags["KY"] = "&#127472;&#127486;";
$emoji_flags["KZ"] = "&#127472;&#127487;";
$emoji_flags["LA"] = "&#127473;&#127462;";
$emoji_flags["LB"] = "&#127473;&#127463;";
$emoji_flags["LC"] = "&#127473;&#127464;";
$emoji_flags["LI"] = "&#127473;&#127470;";
$emoji_flags["LK"] = "&#127473;&#127472;";
$emoji_flags["LR"] = "&#127473;&#127479;";
$emoji_flags["LS"] = "&#127473;&#127480;";
$emoji_flags["LT"] = "&#127473;&#127481;";
$emoji_flags["LU"] = "&#127473;&#127482;";
$emoji_flags["LV"] = "&#127473;&#127483;";
$emoji_flags["LY"] = "&#127473;&#127486;";
$emoji_flags["MA"] = "&#127474;&#127462;";
$emoji_flags["MC"] = "&#127474;&#127464;";
$emoji_flags["MD"] = "&#127474;&#127465;";
$emoji_flags["ME"] = "&#127474;&#127466;";
$emoji_flags["MF"] = "&#127474;&#127467;";
$emoji_flags["MG"] = "&#127474;&#127468;";
$emoji_flags["MH"] = "&#127474;&#127469;";
$emoji_flags["MK"] = "&#127474;&#127472;";
$emoji_flags["ML"] = "&#127474;&#127473;";
$emoji_flags["MM"] = "&#127474;&#127474;";
$emoji_flags["MN"] = "&#127474;&#127475;";
$emoji_flags["MO"] = "&#127474;&#127476;";
$emoji_flags["MP"] = "&#127474;&#127477;";
$emoji_flags["MQ"] = "&#127474;&#127478;";
$emoji_flags["MR"] = "&#127474;&#127479;";
$emoji_flags["MS"] = "&#127474;&#127480;";
$emoji_flags["MT"] = "&#127474;&#127481;";
$emoji_flags["MU"] = "&#127474;&#127482;";
$emoji_flags["MV"] = "&#127474;&#127483;";
$emoji_flags["MW"] = "&#127474;&#127484;";
$emoji_flags["MX"] = "&#127474;&#127485;";
$emoji_flags["MY"] = "&#127474;&#127486;";
$emoji_flags["MZ"] = "&#127474;&#127487;";
$emoji_flags["NA"] = "&#127475;&#127462;";
$emoji_flags["NC"] = "&#127475;&#127464;";
$emoji_flags["NE"] = "&#127475;&#127466;";
$emoji_flags["NF"] = "&#127475;&#127467;";
$emoji_flags["NG"] = "&#127475;&#127468;";
$emoji_flags["NI"] = "&#127475;&#127470;";
$emoji_flags["NL"] = "&#127475;&#127473;";
$emoji_flags["NO"] = "&#127475;&#127476;";
$emoji_flags["NP"] = "&#127475;&#127477;";
$emoji_flags["NR"] = "&#127475;&#127479;";
$emoji_flags["NU"] = "&#127475;&#127482;";
$emoji_flags["NZ"] = "&#127475;&#127487;";
$emoji_flags["OM"] = "&#127476;&#127474;";
$emoji_flags["PA"] = "&#127477;&#127462;";
$emoji_flags["PE"] = "&#127477;&#127466;";
$emoji_flags["PF"] = "&#127477;&#127467;";
$emoji_flags["PG"] = "&#127477;&#127468;";
$emoji_flags["PH"] = "&#127477;&#127469;";
$emoji_flags["PK"] = "&#127477;&#127472;";
$emoji_flags["PL"] = "&#127477;&#127473;";
$emoji_flags["PM"] = "&#127477;&#127474;";
$emoji_flags["PN"] = "&#127477;&#127475;";
$emoji_flags["PR"] = "&#127477;&#127479;";
$emoji_flags["PS"] = "&#127477;&#127480;";
$emoji_flags["PT"] = "&#127477;&#127481;";
$emoji_flags["PW"] = "&#127477;&#127484;";
$emoji_flags["PY"] = "&#127477;&#127486;";
$emoji_flags["QA"] = "&#127478;&#127462;";
$emoji_flags["RE"] = "&#127479;&#127466;";
$emoji_flags["RO"] = "&#127479;&#127476;";
$emoji_flags["RS"] = "&#127479;&#127480;";
$emoji_flags["RU"] = "&#127479;&#127482;";
$emoji_flags["RW"] = "&#127479;&#127484;";
$emoji_flags["SA"] = "&#127480;&#127462;";
$emoji_flags["SB"] = "&#127480;&#127463;";
$emoji_flags["SC"] = "&#127480;&#127464;";
$emoji_flags["SD"] = "&#127480;&#127465;";
$emoji_flags["SE"] = "&#127480;&#127466;";
$emoji_flags["SG"] = "&#127480;&#127468;";
$emoji_flags["SH"] = "&#127480;&#127469;";
$emoji_flags["SI"] = "&#127480;&#127470;";
$emoji_flags["SJ"] = "&#127480;&#127471;";
$emoji_flags["SK"] = "&#127480;&#127472;";
$emoji_flags["SL"] = "&#127480;&#127473;";
$emoji_flags["SM"] = "&#127480;&#127474;";
$emoji_flags["SN"] = "&#127480;&#127475;";
$emoji_flags["SO"] = "&#127480;&#127476;";
$emoji_flags["SR"] = "&#127480;&#127479;";
$emoji_flags["SS"] = "&#127480;&#127480;";
$emoji_flags["ST"] = "&#127480;&#127481;";
$emoji_flags["SV"] = "&#127480;&#127483;";
$emoji_flags["SX"] = "&#127480;&#127485;";
$emoji_flags["SY"] = "&#127480;&#127486;";
$emoji_flags["SZ"] = "&#127480;&#127487;";
$emoji_flags["TC"] = "&#127481;&#127464;";
$emoji_flags["TD"] = "&#127481;&#127465;";
$emoji_flags["TF"] = "&#127481;&#127467;";
$emoji_flags["TG"] = "&#127481;&#127468;";
$emoji_flags["TH"] = "&#127481;&#127469;";
$emoji_flags["TJ"] = "&#127481;&#127471;";
$emoji_flags["TK"] = "&#127481;&#127472;";
$emoji_flags["TL"] = "&#127481;&#127473;";
$emoji_flags["TM"] = "&#127481;&#127474;";
$emoji_flags["TN"] = "&#127481;&#127475;";
$emoji_flags["TO"] = "&#127481;&#127476;";
$emoji_flags["TR"] = "&#127481;&#127479;";
$emoji_flags["TT"] = "&#127481;&#127481;";
$emoji_flags["TV"] = "&#127481;&#127483;";
$emoji_flags["TW"] = "&#127481;&#127484;";
$emoji_flags["TZ"] = "&#127481;&#127487;";
$emoji_flags["UA"] = "&#127482;&#127462;";
$emoji_flags["UG"] = "&#127482;&#127468;";
$emoji_flags["UM"] = "&#127482;&#127474;";
$emoji_flags["US"] = "&#127482;&#127480;";
$emoji_flags["UY"] = "&#127482;&#127486;";
$emoji_flags["UZ"] = "&#127482;&#127487;";
$emoji_flags["VA"] = "&#127483;&#127462;";
$emoji_flags["VC"] = "&#127483;&#127464;";
$emoji_flags["VE"] = "&#127483;&#127466;";
$emoji_flags["VG"] = "&#127483;&#127468;";
$emoji_flags["VI"] = "&#127483;&#127470;";
$emoji_flags["VN"] = "&#127483;&#127475;";
$emoji_flags["VU"] = "&#127483;&#127482;";
$emoji_flags["WF"] = "&#127484;&#127467;";
$emoji_flags["WS"] = "&#127484;&#127480;";
$emoji_flags["XK"] = "&#127485;&#127472;";
$emoji_flags["YE"] = "&#127486;&#127466;";
$emoji_flags["YT"] = "&#127486;&#127481;";
$emoji_flags["ZA"] = "&#127487;&#127462;";
$emoji_flags["ZM"] = "&#127487;&#127474;";
$emoji_flags["ZW"] = "&#127487;&#127484;";	
		
		
		return $emoji_flags[$code];
		}
	
	public function code_to_country( $code, $get_list=false ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo - Kinshasa',
        'CG' => 'Congo - Brazzaville',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => "CI",
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'North Korea',
        'KR' => 'South Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russia',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
		'SS' => 'SS',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'SJ',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

	if($get_list)
		return $countryList;

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }
	
	public function file_get_contents_utf8($fn) {
		 $content = file_get_contents($fn);
		  return mb_convert_encoding($content, 'UTF-8',
			  mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
	}
	

	public function get_geo_location($ipaddress){
			$response = wp_remote_get( "http://ipinfo.io/{$ipaddress}/json" );
			$output   = wp_remote_retrieve_body( $response );
      		return $output;
		}
			
		public function isJson($string) {
		 json_decode($string);
		 return (json_last_error() === JSON_ERROR_NONE);
		}
		
		public function get_ext($filename) {
			return (($pos = strrpos($filename, '.')) !== false ? substr($filename, $pos+1) : '');
		}
		
		public function format_date($str){
			$datetime = explode(' ',$str);
			$time = explode(':',$datetime[1]);
			$date = explode('/',$datetime[0]);
			return date(get_option('date_format'),mktime('0','0','0',$date[0],$date[1],$date[2]));
		}
		
		public function format_name($str){
			
			$str = trim($str);
			$str = strtolower($str);		
			$str = str_replace('’','',$str);
			$str = str_replace('  ',' ',$str);
			$str = str_replace(' ','_',$str);
			$str = str_replace('{{','',$str);
			$str = str_replace('}}','',$str);
			$str = str_replace('[]','',$str);
			$str = str_replace(')','',$str);
			$str = str_replace('(','',$str);
			$str = str_replace('%20','_',$str);
			
			if($str=='name')
				$str = '_'.$str;
			
			return trim($str);
		}
		
		public function format_column_name($str){
			
			$str = trim($str);
			$str = strtolower($str);	
			$str = str_replace('’','',$str);
			$str = str_replace('¿','',$str);
			$str = str_replace('  ',' ',$str);
			$str = str_replace(' ','_',$str);
			$str = str_replace('-','_',$str);
			$str = str_replace(':','_',$str);
			
			//$str = str_replace(':','',$str);
			
			//$str = preg_replace('/[^A-Za-z0-9_]/', '', $str);
			
			$utf8 = array(
				
				'/[áàâãªä]/u'   =>   'a',
				'/[ÁÀÂÃÄ]/u'    =>   'A',
				'/[ÍÌÎÏ]/u'     =>   'I',
				'/[íìîï]/u'     =>   'i',
				'/[éèêë]/u'     =>   'e',
				'/[ÉÈÊË]/u'     =>   'E',
				'/[óòôõºö]/u'   =>   'o',
				'/[ÓÒÔÕÖ]/u'    =>   'O',
				'/[úùûü]/u'     =>   'u',
				'/[ÚÙÛÜ]/u'     =>   'U',
				'/ç/'           =>   'c',
				'/Ç/'           =>   'C',
				'/ñ/'           =>   'n',
				'/Ñ/'           =>   'N',
				'/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
				'/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
				'/[“”«»„]/u'    =>   ' ', // Double quote
				'/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
			);
			
			$str = strtr($str, array(
    'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ª' => 'a', 'ä' => 'a',
    'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
    'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
    'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
    'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
    'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
    'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'º' => 'o', 'ö' => 'o',
    'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O',
    'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
    'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
    'ç' => 'c', 'Ç' => 'C',
    'ñ' => 'n', 'Ñ' => 'N',
    '–' => '_', '’' => '_', '‘' => '_', '‹' => '_', '›' => '_', '‚' => '_',
    '“' => '_', '”' => '_', '«' => '_', '»' => '_', '„' => '_',
));
			
			$colname = substr($str,0,64);
			
			return $colname;
		}
		
		
		public function unformat_name($str, $chars=false){
			
			$nf_functions 		= new NEXForms_Functions();
			
			$str = $nf_functions->format_name($str);
			
			$str = str_replace('u2019','\'',$str);
			$str = str_replace('_',' ',$str);
			$str = str_replace('[','',$str);
			$str = str_replace(']','',$str);
			$str = ucfirst(trim($str));
			if($str=='nr')
				{
				$str = str_replace('nr','Nr.',$str);
				}
			if($str=='art' || $str=='Art')
				{
				$str = str_replace('art','Art.',$str);
				$str = str_replace('Art','Art.',$str);
				}
			if($str=='eur' || $str=='Eur')
				{
				$str = str_replace('eur','EUR',$str);
				$str = str_replace('Eur','EUR',$str);
				}
			if($str=='Chf' || $str=='chf')
				{
				$str = str_replace('Chf','CHF',$str);
				$str = str_replace('chf','CHF',$str);
				}
			
			if($chars)
				$str = substr($str,0,$chars);
			return trim($str);
		}
		
		public function unformat_records_name($str, $chars=false){
			
			$nf_functions 		= new NEXForms_Functions();
			
			$str = $nf_functions->format_name($str);
			
			$str = str_replace('u2019','\'',$str);
			$str = str_replace('_',' ',$str);
			$str = str_replace('[','',$str);
			$str = str_replace(']','',$str);
			if($chars)
				$str = substr($str,0,$chars);
			return trim($str);
		}
			
		
		public function get_file_headers($file){
				
			$default_headers = array(			
				'Module Name' 		=> 'Module Name',
				'For Plugin' 		=> 'For Plugin',
				'Module Prefix'		=> 'Module Prefix',
				'Module URI' 		=> 'Module URI',
				'Module Scope' 		=> 'Module Scope',
				
				'Plugin Name' 		=> 'Plugin Name',
				'Plugin TinyMCE' 	=> 'Plugin TinyMCE',
				'Plugin Prefix'		=> 'Plugin Prefix',
				'Plugin URI' 		=> 'Plugin URI',
				'Module Ready' 		=> 'Module Ready',
				
				'Version' 			=> 'Version',
				'Description' 		=> 'Description',
				'Author' 			=> 'Author',
				'AuthorURI' 		=> 'Author URI'
			);
			return get_file_data($file,$default_headers,'module');
		}
		
		
		public function do_upload_image() {
			if(!current_user_can( NF_USER_LEVEL ))	
				wp_die();
			foreach($_FILES as $key=>$file)
				{
				$uploadedfile = $_FILES[$key];
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				//
				if ( $movefile )
					{
					//echo "File is valid, and was successfully uploaded.\n";
					if($movefile['file'])
						{
						$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
						$_POST['image_path'] = $movefile['url'];
						$_POST['image_name'] = $file['name'];
						$_POST['image_size'] = $file['size'];
						
						$dimention = getimagesize($movefile['file']);
						
						NEXForms_clean_echo( json_encode(array('image_url'=>$movefile['url'], 'image_size'=>$file, 'dimention'=>$dimention)));
						}
					} 
				}
			
			die();
		}
	
	
	public function view_excerpt($content,$chars=0){
			$content = wp_strip_all_tags($content);
			$excerpt = '';
			for($i=0;$i<$chars;$i++){
				$excerpt .= substr($content,$i,1);
			}
			
			if(strlen($content)>$chars)
				{
				$set_excerpt = '<span class="" data-position="top" data-delay="50" data-html="true" title="'.$content.'">'.$excerpt.'&hellip;</span>';
				}
			else
				{
				$set_excerpt = $excerpt;
				}
			
			return str_replace('\\','',$set_excerpt);
		}
	public function view_excerpt2($content,$chars=0){
			$content = wp_strip_all_tags($content);
			$excerpt = '';
			for($i=0;$i<$chars;$i++){
				$excerpt .= substr($content,$i,1);
			}
			
			if(strlen($content)>$chars)
				{
				$set_excerpt = $excerpt.'&hellip;';
				}
			else
				{
				$set_excerpt = $excerpt;
				}
			
			return str_replace('\\','',$set_excerpt);
		}
	
	public function print_preloader($size='big',$color='blue',$hidden=true,$class=''){
			$output = '';
			$output .= '<div class="preload '.$class.' '.(($hidden) ? 'hidden' : '').'">';
				$output .= '<div class="preloader-wrapper '.$size.' active">';
				$output .= '<div class="spinner-layer spinner-'.$color.'-only">';
				$output .= '<div class="circle-clipper left">';
				$output .= '<div class="circle"></div>';
				$output .= '</div><div class="gap-patch">';
				$output .= '<div class="circle"></div>';
				$output .= '</div><div class="circle-clipper right">';
				$output .= '<div class="circle"></div>';
				$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>	';
			
			return $output;
		}
	
	
	
	public function run_old_conditional_logic($logic, $unigue_form_Id){
			$rules = explode('[start_rule]',$logic);
		$i=1;
	
	$output = '';
	$print_auto_hide = '';
	$function_post_fix = wp_rand(1,99999999);
	
	$output .= '<script type="text/javascript" name="js_con">
	
	function test_run_nf_conditional_logic'.$function_post_fix.'(){
			';		
		foreach($rules as $rule)
			{
			if($rule)
				{
				$operator =  explode('[operator]',$rule);
				$operator2 =  explode('[end_operator]',$operator[1]);
				$get_operator = trim($operator2[0]);
				
				$get_operator2 = explode('##',$get_operator);
				$rule_operator = $get_operator2[0];
				$reverse_action = $get_operator2[1];
				
				
				if($rule_operator=='any')
					$if_clause = ' || ';
				else
					$if_clause = ' && ';
					
				$conditions =  explode('[conditions]',$rule);
				$conditions2 =  explode('[end_conditions]',$conditions[1]);
				$rule_conditions = trim($conditions2[0]);
	
				$get_conditions =  explode('[new_condition]',$rule_conditions);
				$get_conditions2 =  explode('[end_new_condition]',$get_conditions[1]);
				$get_rule_conditions = trim($get_conditions2[0]);
				
				
				$output .= 'if(';
				
				$query_length = count($get_conditions);
				$i = 0;
				foreach($get_conditions as $set_condition)
					{
					
					$the_condition 		=  explode('[field_condition]',$set_condition);
					$the_condition2 	=  explode('[end_field_condition]',$the_condition[1]);
					$get_the_condition 	=  trim($the_condition2[0]);
					
					$the_value 		=  explode('[value]',$set_condition);
					$the_value2 	=  explode('[end_value]',$the_value[1]);
					$get_the_value 	=  trim($the_value2[0]);
						
					
					$con_field =  explode('[field]',$set_condition);
					$con_field2 =  explode('[end_field]',$con_field[1]);
					$get_con_field = explode('##',$con_field2[0]);;
					
					$con_field_type = $get_con_field[0];
					
					$get_con_field_attr = explode('**',$get_con_field[0]);
					
					$con_field_id	 = $get_con_field_attr[0];
					$con_field_type	 = $get_con_field_attr[1];
					$con_field_name	 = $get_con_field[1];
					
					$set_operator = '==';
					
					if($con_field_type)
						{
						if($get_the_condition=='equal_to')	
							$set_operator = '==';
						elseif($get_the_condition=='not_equal_to')
							$set_operator = '!=';
						elseif($get_the_condition=='less_than')
							$set_operator = '<';
						elseif($get_the_condition=='greater_than')
							$set_operator = '>';
						elseif($get_the_condition=='less_equal')
							$set_operator = '<=';
						elseif($get_the_condition=='greater_equal')
							$set_operator = '>=';	
							
						
						if($con_field_type=='radio')	
							$add_string = ':checked';
						elseif($con_field_type=='checkbox')
							$add_string = ':checked';
						else
							$add_string = '';
							
						if (is_numeric($get_the_value)) 
							$set_the_value = '('.$get_the_value.')';
						else
							$set_the_value = '"'.$get_the_value.'"';
							
						
						if($con_field_type=='select')
							{
							$output .= 'jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'select option:selected\').val()'.$set_operator.''.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
							}
						else if($con_field_type=='textarea')
							{
							$output .= 'jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'textarea\').val()'.$set_operator.''.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
							}
						else
							$output .= 'jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'input[type="'.$con_field_type.'"]'.$add_string.'\').val()'.$set_operator.''.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );

						}
						$i++;
					}
					$output .= '){
						';
				
				$actions =  explode('[actions]',$rule);
				$actions2 =  explode('[end_actions]',$actions[1]);
				$rule_actions = trim($actions2[0]);
				
				$get_actions =  explode('[new_action]',$rule_actions);
				$get_actions2 =  explode('[end_new_action]',$get_actions[1]);
				$get_rule_actions = trim($get_actions2[0]);
				
				foreach($get_actions as $set_action)
					{
					
					$action_to_take =  explode('[the_action]',$set_action);
					$action_to_take2 =  explode('[end_the_action]',$action_to_take[1]);
					$get_action_to_take = trim($action_to_take2[0]);
					
					$action_field =  explode('[field_to_action]',$set_action);
					$action_field2 =  explode('[end_field_to_action]',$action_field[1]);
					$get_action_field = explode('##',$action_field2[0]);
					
					$action_field_type = $get_action_field[0];
					
					$get_action_field_attr = explode('**',$get_action_field[0]);
					
					$action_field_id	 = $get_action_field_attr[0];
					$action_field_type	 = $get_action_field_attr[1];
					$action_field_name	 = $get_action_field[1];
					
					
					
					if($action_field_type)
						{
						$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$get_action_to_take.'();';
						}
						
					}
				$output .= '
				}
			else
				{';
			
			
			foreach($get_actions as $set_action)
					{
					
					$action_to_take =  explode('[the_action]',$set_action);
					$action_to_take2 =  explode('[end_the_action]',$action_to_take[1]);
					$get_action_to_take = trim($action_to_take2[0]);
					
					$action_field =  explode('[field_to_action]',$set_action);
					$action_field2 =  explode('[end_field_to_action]',$action_field[1]);
					$get_action_field = explode('##',$action_field2[0]);
					
					$action_field_type = $get_action_field[0];
					
					$get_action_field_attr = explode('**',$get_action_field[0]);
					
					$action_field_id	 = $get_action_field_attr[0];
					$action_field_type	 = $get_action_field_attr[1];
					$action_field_name	 = $get_action_field[1];
					
					
					
					if($action_field_type)
						{
						if($get_action_to_take=='show')
							$set_reverse_action = 'hide';
						if($get_action_to_take=='hide')
							$set_reverse_action = 'show';
							
						if($reverse_action=='true' || !$reverse_action)
							$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$set_reverse_action.'();';
							
						$print_auto_hide .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").hide();
						';
						
						}
						
					}
				$output .= '
			}';
				}
				
				$output .= '';
			}
	$output .= '
		}
		jQuery(document).ready(
			function()
				{
					'.$print_auto_hide.'
					
					
					jQuery(document).on(\'change\', \'#nex-forms input, #nex-forms select, #nex-forms textarea\',
						function()
							{
							//test_run_nf_conditional_logic'.$function_post_fix.'()
							}
						);
				}
			);
		</script>';
	
	return $output;	
	}
	
	
public function run_conditional_logic($logic, $unigue_form_Id){
			
			
			
	$rules = $logic;
	$i=1;
	
	$output = '';
	$con_count = 0;
	$print_auto_hide = '';
	$function_post_fix = wp_rand(1,99999999);
	
	
	//echo '<pre>';
	//print_r($rules);
	//echo '</pre>';
	
	if(!empty($rules))
		{
	
		$output .= '
		
		function run_nf_conditional_logic'.$function_post_fix.'(obj){';		
				
			foreach($rules as $rule)
				{
				foreach($rule->conditions as $condition)
						{
						$con_count++;
						}
				}
			//echo $con_count;
			foreach($rules as $rule)
				{
				if($rule)
					{
					
					$rule_operator 	= $rule->operator;
					$reverse_action = $rule->reverse_actions;
					
					$if_clause = ' || ';
					
					if($rule_operator=='any')
						$if_clause = ' || ';
					else
						$if_clause = ' && ';
				
					$rule_con_count = 0;
					
					foreach($rule->conditions as $condition)
						{
						$rule_con_count++;
						}
					
					$query_length = $rule_con_count;
					$i = 0;
					if($rule_con_count!=0)
						{
					
					$check_values = '[]';
					
					if( $condition->field_type=='checkbox' && $rule_operator=='any')
						{
						$output .= 'var action_targets 		= [];';	
							
							$output .= 'jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$condition->field_Id.'\').find(\'input[type="checkbox"]\').each(
										function()
											{
											if(jQuery(this).prop("checked")===true && jQuery(this).val()==\''.$condition->condition_value.'\')
												{
													
												//console.log(jQuery(this).val() + "  "+ jQuery(this).prop("checked"));
												';
											
										foreach($rule->actions as $action)
											{
											$get_action_to_take = $action->do_action;
											
											$action_field_id	 = $action->target_field_Id;
											$action_field_type	 = $action->target_field_name;
											$action_field_name	 = $action->target_field_type;
											
											if($action_field_type)
												{
												$output .= '
												
												action_targets.push("'.$action_field_id.'");
												
												//jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$get_action_to_take.'();';
												}
													
												} 
										$output .='
												
												
												}
											}
										);
										
										
											';
											
										foreach($rule->actions as $action)
											{
											$get_action_to_take = $action->do_action;
											
											$action_field_id	 = $action->target_field_Id;
											$action_field_type	 = $action->target_field_name;
											$action_field_name	 = $action->target_field_type;
											
											if($action_field_type)
												{
												$output .= '
												if(is_inArray(\''.$action_field_id.'\',action_targets) )
													{
													//jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$get_action_to_take.'();
													run_nf_cl_animations(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'"),"'.$get_action_to_take.'",jQuery("#nf_form_'.$unigue_form_Id.'"));
													} ';
												}
												
											} 
									$output .='	
										else 
											{
											';
											foreach($rule->actions as $action)
												{
												$get_action_to_take = $action->do_action;
												
												$action_field_id	 = $action->target_field_Id;
												$action_field_type	 = $action->target_field_name;
												$action_field_name	 = $action->target_field_type;
												
												if($action_field_type)
													{
													if($get_action_to_take=='show')
														$set_reverse_action = 'hide';
													if($get_action_to_take=='hide')
														$set_reverse_action = 'show';
														
													if($reverse_action=='true' || !$reverse_action)
														$output .= 'run_nf_cl_animations(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'"),"'.$set_reverse_action.'",jQuery("#nf_form_'.$unigue_form_Id.'"));';
														//$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$set_reverse_action.'().removeClass("hidden");';
														
													$print_auto_hide .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").hide().removeClass("hidden");
													';
													
													}
													
												}
								$output .= '}';	
						}
					
					else
						{
						
					$output .= '
					if(';
					
					
					
					foreach($rule->conditions as $condition)
						{
						
						$get_the_condition 	=  $condition->condition;
						$get_the_value 		=  $condition->condition_value;
							
						
						$con_field_id	 = $condition->field_Id;
						$con_field_type	 = $condition->field_type;
						$con_field_name	 = $condition->field_name;
						
						if($con_field_type == 'stars')
							$con_field_type = 'hidden';
							
						
						$set_operator = '==';
						
						if($con_field_type)
							{
							if($get_the_condition=='equal_to')	
								$set_operator = '==';
							elseif($get_the_condition=='not_equal_to')
								$set_operator = '!=';
							elseif($get_the_condition=='less_than')
								$set_operator = '<';
							elseif($get_the_condition=='greater_than')
								$set_operator = '>';
							elseif($get_the_condition=='less_equal')
								$set_operator = '<=';
							elseif($get_the_condition=='greater_equal')
								$set_operator = '>=';	
							
							if($con_field_type=='radio')	
								$add_string = ':checked';
							elseif($con_field_type=='checkbox')
								$add_string = ':checked';
							else
								$add_string = '';
							
							
							if($con_field_type=='date')
								$con_field_type = 'text';
							
							if(strstr($get_the_value,'{{'))
								{
								$get_the_value = str_replace('{{','',$get_the_value);
								$get_the_value = str_replace('}}','',$get_the_value);
								
								if($set_operator == '<' || $set_operator == '>' || $set_operator == '<=' || $set_operator == '>=')
									{
									$set_the_value = 'parseFloat(jQuery(\'#nf_form_'.$unigue_form_Id.'\').find(\'[name="'.$get_the_value.'"]\').val())';
								
									}
								else
									{
									$set_the_value = 'jQuery(\'#nf_form_'.$unigue_form_Id.'\').find(\'[name="'.$get_the_value.'"]\').val()';
									}
								}			
							else if ($get_the_value=='null') 
								$set_the_value = 'null';				
							else if (is_numeric($get_the_value)) 
								$set_the_value = '('.$get_the_value.')';
							else
								$set_the_value = 'nf_str_to_lower("'.$get_the_value.'")';
							
							
							if($get_the_condition=='contains' || $get_the_condition=='not_contains')
								{
								$set_bool = ($get_the_condition=='contains') ? '' : '!'; 
								
								if($con_field_type=='select')
									{
									$output .= $set_bool.'strstr(nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'select option:selected\').val()), '.$set_the_value.') '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else if($con_field_type=='textarea')
									{
									$output .= $set_bool.'strstr(nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'textarea\').val()), '.$set_the_value.') '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else if($con_field_id=='hidden_field')
									{
									$output .= $set_bool.'strstr(nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.'\').find(\'input[name="'.$con_field_name.'"][type="hidden"]\').val()), '.$set_the_value.') '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else if($set_operator == '<' || $set_operator == '>' || $set_operator == '<=' || $set_operator == '>=')
									{
									$output .= $set_bool.'strstr(parseFloat(nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'input[type="'.$con_field_type.'"]'.$add_string.'\').val())), '.$set_the_value.') '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else
									{
									$output .= $set_bool.'strstr(nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'input[type="'.$con_field_type.'"]'.$add_string.'\').val()), '.$set_the_value.') '.(($i<($query_length-1)) ? $if_clause : '' );
									}
									
								}
							else
								{
								if($con_field_type=='select')
									{
									$output .= 'nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'select option:selected\').val()) '.$set_operator.' '.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else if($con_field_type=='textarea')
									{
									$output .= 'nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'textarea\').val()) '.$set_operator.' '.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else if($con_field_id=='hidden_field')
									{
									$output .= 'nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.'\').find(\'input[name="'.$con_field_name.'"][type="hidden"]\').val()) '.$set_operator.' '.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else if($set_operator == '<' || $set_operator == '>' || $set_operator == '<=' || $set_operator == '>=')
									{
									$output .= 'parseFloat(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'input[type="'.$con_field_type.'"]'.$add_string.'\').val()) '.$set_operator.' '.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								else
									{
									$output .= 'nf_str_to_lower(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').find(\'input[type="'.$con_field_type.'"]'.$add_string.'\').val()) '.$set_operator.' '.$set_the_value.' '.(($i<($query_length-1)) ? $if_clause : '' );
									}
								}
							}
							$i++;
						}
						$output .= '){
							
							
							
							';
					
					
					foreach($rule->actions as $action)
						{
						$get_action_to_take = $action->do_action;
						
						$action_field_id	 = $action->target_field_Id;
						$action_field_type	 = $action->target_field_type;
						$action_field_name	 = $action->target_field_name;
						$action_to_value	 = '"'.$action->change_value.'"';
						$set_action_to_take2 = '';
						
						if(strstr($action_to_value,'{{'))
								{
								$action_to_value = str_replace('{{','',$action_to_value);
								$action_to_value = str_replace('}}','',$action_to_value);
								$action_to_value = 'jQuery(\'#nf_form_'.$unigue_form_Id.'\').find(\'[name='.$action_to_value.']\').val()';
								}
						
						
						
						if($get_action_to_take=='show' || $get_action_to_take == 'hide')
							$set_action_to_take = $get_action_to_take.'().removeClass("hidden");';
						if($get_action_to_take=='disable')
							{
							$set_action_to_take = 'find("input, textarea, select, button").attr("disabled",true);
							';
							$set_action_to_take2 = 'addClass("nf_field_disabled");';
							}
						if($get_action_to_take=='enable')
							{
							$set_action_to_take = 'find("input, textarea, select, button").attr("disabled",false);
							';
							$set_action_to_take2 = 'removeClass("nf_field_disabled");';
							}
						if($action_field_type)
							{
							
							if($action_field_type=='step')
								{
								if($get_action_to_take=='show')
									{
									$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").removeClass("hidden_by_logic").addClass("step");
												var get_bread = parseInt(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").attr("data-step-num"));
												jQuery("#nf_form_'.$unigue_form_Id.'").find(".the_br li:nth-child(" + (get_bread) + ")").removeClass("hidden_by_logic");
												';	
									}
								else if($get_action_to_take=='hide')
									{
									$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").addClass("hidden_by_logic").removeClass("step");
												var get_bread = parseInt(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").attr("data-step-num"));
												jQuery("#nf_form_'.$unigue_form_Id.'").find(".the_br li:nth-child(" + (get_bread) + ")").addClass("hidden_by_logic");
												';
									
									}
								else if($get_action_to_take=='skip_to')
									{
									$output .= '
									if(obj)
										{
										if(jQuery(\'#nf_form_'.$unigue_form_Id.' #'.$con_field_id.'\').is(":visible"))
											obj.closest(".step").find(".nex-step").attr("data-skip-to","'.$action_field_name.'");
									
										}
										
										';
										
										
									}
									
								}
							else
								{
								
								if($get_action_to_take=='change_value')
									{
									
									if($action_field_type=='text' || $action_field_type=='hidden')
										{
										
										if($action_field_id=='hidden_field')
											{
											$output .= 'jQuery("#nf_form_'.$unigue_form_Id.'").find(\'input[name="'.$action_field_name.'"]\').val('.$action_to_value.');';
											$output .= 'jQuery("#nf_form_'.$unigue_form_Id.'").find(\'input[name="'.$action_field_name.'"]\').trigger("do_nf_math_event");';	
											}
										else
											{
										
											$output .= 'if(!jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").hasClass("is_typing"))
												{';
												$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').val('.$action_to_value.');';
												
												$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').trigger("do_nf_math_event");';
												
												
												$output .= '
												if(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').hasClass(\'the_slider\'))
													{
													setTimeout(function(){
													jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').parent().find(\'#slider\').slider({ value: '.$action_to_value.' });
													jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'.count-text\').html(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').parent().find(\'#slider\').attr(\'data-count-text\').replace(\'{x}\','.$action_to_value.'));
													},100);
													}
												';
											
												$output .= '
											  }';	
											}
										}
									if($action_field_type=='textarea')
										{
										$output .= 'if(!jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").hasClass("is_typing")){';
										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').val('.$action_to_value.');';	
										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input\').trigger("do_nf_math_event");}';	
										}
									if($action_field_type=='radio')
										{
											

										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input[type="radio"]\').each(
													function()
														{
														if(jQuery(this).val()=='.$action_to_value.')
															jQuery(this).closest(\'label\').trigger(\'click\');
														}
													);';
											
										}
									if($action_field_type=='checkbox')
										{
											
										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'input[type="checkbox"]\').each(
														function()
															{
															if(jQuery(this).val()=='.$action_to_value.')
																jQuery(this).closest(\'label\').trigger(\'click\');
															}
														)';
											
										}	
									if($action_field_type=='select')
										{
											
										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").find(\'select option\').each(
														function()
															{
															if(jQuery(this).attr(\'value\')=='.$action_to_value.')
																jQuery(this).prop(\'selected\',true);
																//jQuery(this).trigger(\'click\');
															}
														)';
											
										}		
										
										
									}
								else
									{
									if($get_action_to_take != 'show' && $get_action_to_take != 'hide') 	
										{
										if($get_action_to_take=='disable')
											$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").addClass("disabled");';
											
											
										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$set_action_to_take;
										if($set_action_to_take2!='')
											$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$set_action_to_take2;
										$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").removeClass("nf-has-error").removeClass("has_error").find(".error_msg.modern").remove();';
										}
									else
										$output .= 'run_nf_cl_animations(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'"),"'.$get_action_to_take.'",jQuery("#nf_form_'.$unigue_form_Id.'"));';
									}
								}
							}
							
						}
					$output .= '
					}
				else
					{';
					foreach($rule->actions as $action)
						{
						$get_action_to_take = $action->do_action;
						
						$action_field_id	 = $action->target_field_Id;
						$action_field_type	 = $action->target_field_type;
						$action_field_name	 = $action->target_field_name;
						$set_reverse_action2 = '';
						
						if($action_field_name)
							{
							if($get_action_to_take=='show')
								$set_reverse_action = 'hide()';
							if($get_action_to_take=='hide')
								$set_reverse_action = 'show()';
							
							if($get_action_to_take=='disable'){
								$set_reverse_action = 'find("input, textarea, select, button").prop("disabled",false);
								';
								$set_reverse_action2 = 'removeClass("nf_field_disabled");';
							}
							if($get_action_to_take=='enable')
								{
								$set_reverse_action = 'find("input, textarea, select, button").prop("disabled",true);
								';
								$set_reverse_action2 = 'addClass("nf_field_disabled");';
								}
								
							if($reverse_action=='true' || !$reverse_action)
								{
								
								if($action_field_type=='step')
									{
									if($get_action_to_take=='show'){
										$output .= '
										jQuery("#nf_form_'.$unigue_form_Id.'  #'.$action_field_id.'").addClass("hidden_by_logic").removeClass("step");
										var get_bread = parseInt(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").attr("data-step-num"));
										jQuery("#nf_form_'.$unigue_form_Id.'").find(".the_br li:nth-child(" + (get_bread) + ")").addClass("hidden_by_logic");
										';	
										
									}
									else if($get_action_to_take=='hide')
										$output .= '
										jQuery("#nf_form_'.$unigue_form_Id.'  #'.$action_field_id.'").removeClass("hidden_by_logic").addClass("step");
										var get_bread = parseInt(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").attr("data-step-num"));
										jQuery("#nf_form_'.$unigue_form_Id.'").find(".the_br li:nth-child(" + (get_bread) + ")").removeClass("hidden_by_logic");
										';
										
									else if($get_action_to_take=='skip_to')
										{
										//$output .= 'obj.closest(".step").attr("data-skip-to","'.$action_field_name.'");';
										}
									}
								else
									{
									if($get_action_to_take=='change_value')
										{
										if($action_field_type=='text' || $action_field_type=='hidden')
											{
										
											if($action_field_id=='hidden_field')
												{
												$output .= 'jQuery("#nf_form_'.$unigue_form_Id.'").find(\'input[name="'.$action_field_name.'"]\').val(jQuery("#nf_form_'.$unigue_form_Id.'").find(\'input[name="'.$action_field_name.'"]\').attr("data-original-value"));';
												$output .= 'jQuery("#nf_form_'.$unigue_form_Id.'").find(\'input[name="'.$action_field_name.'"]\').trigger("do_nf_math_event");';	
												}
											
											}
										}
									else
										{
										if($get_action_to_take != 'show' && $get_action_to_take != 'hide') 	
											{
											
											if($get_action_to_take=='disable')
												$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").removeClass("disabled");';
											
											
											$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$set_reverse_action.';';
											if($set_reverse_action2!='')
												$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").'.$set_reverse_action2.';';
											$output .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").removeClass("nf-has-error").removeClass("has_error").find(".error_msg.modern").remove();';
											}
										else
											$output .= 'run_nf_cl_animations(jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'"),"'.$set_reverse_action.'",jQuery("#nf_form_'.$unigue_form_Id.'"));';
									
										}
									}
								
								}
							if($get_action_to_take!='disable' && $get_action_to_take!='enable' && $get_action_to_take!='change_value')
								$print_auto_hide .= 'jQuery("#nf_form_'.$unigue_form_Id.' #'.$action_field_id.'").hide().removeClass("hidden");
							';
							
							}
							
						}
					$output .= '
				}';
							}
					}
					
					$output .= '';
				}
			}
		
		
		$output .= '
			}
		setTimeout(function(){ 
					
					
					
					
					
					
		jQuery(document).ready(function() {
				
					'.$print_auto_hide.'
					jQuery(document).on(\'change\', \'#nex-forms input , #nex-forms select, #nex-forms textarea\',
						function()
							{
							var the_obj = jQuery(this);
							if(jQuery(this).is(":checkbox")){
							  setTimeout(function(){ run_nf_conditional_logic'.$function_post_fix.'(the_obj); }, 120);
							}
							else
								run_nf_conditional_logic'.$function_post_fix.'(jQuery(this));
							}
						);
					jQuery(document).on(\'keyup\', \'#nex-forms input, #nex-forms textarea\',
						function()
							{
							run_nf_conditional_logic'.$function_post_fix.'(jQuery(this));
							}
						);
					
					
					run_nf_conditional_logic'.$function_post_fix.'("");

				})
				}, 1000);
			';
		}
		
	/*echo '<div style="width:400px;">';
				echo $output;
			echo '</div>';*/
	return $output;	
	}

	
	}
}	

/*function add_nf_free_add_ons_notice_dismissible() {
    global $pagenow;
   if ($pagenow == 'admin.php' ) {
	   	if(isset($_REQUEST['page']) && ( $_REQUEST['page']=='nex-forms-dashboard'))//$_REQUEST['page']=='nex-forms-dashboard' ||
			{
				 echo '<div class="notice notice-warning dismiss_nf_notice is-dismissible">
					 <p><strong>NEX-FORMS NOTICE:</strong>You are eligable to get all these add-ons for free! Please go to <a href="http://basix.ticksy.com" target="_blank">http://basix.ticksy.com</a> with your NEX-Forms Purchase Code to claim your add-ons.</p>
				 </div>';
			}
    }
}

if( get_option( 'dismiss_nf_notice_free_add_ons' ) != true ) {
   // add_action( 'admin_notices', 'add_nf_free_add_ons_notice_dismissible' );
}


add_action( 'wp_ajax_dismiss_nf_free_add_on_notice', 'dismiss_nf_free_add_on_notice' );
function dismiss_nf_free_add_on_notice(){
      update_option( 'dismiss_nf_notice_free_add_ons', true );
	  die();
}

*/
function add_nf_wf_notice_dismissible() {
    global $pagenow;
   if ($pagenow == 'admin.php' ) {
	   	if(isset($_REQUEST['page']) && ( $_REQUEST['page']=='nex-forms-builder'))//$_REQUEST['page']=='nex-forms-dashboard' ||
			{
			if(class_exists('wordfence'))
				{
				 NEXForms_clean_echo( '<div class="notice notice-warning dismiss_nf_notice is-dismissible">
					 <p><strong>NEX-FORMS NOTICE:</strong> <strong>WordFence currently active</strong><br /><br />If you have issues saving a form, i.e: if the SAVE BUTTON KEEPS SPINNING...<br /><strong>WHAT TO DO</strong>? 
					 <br /><br />
					 <strong>OPTION 1: </strong><br />Whitelist your own IP address in your WordFence Firewall. <br /><a href="'.get_option('siteurl').'/wp-admin/admin.php?page=WordfenceOptions" target="_blank">See <strong>Advanced Firewall Options</strong> -> Whitelisted IP addresses that bypass all rules</a>.<br /><br />
					 <strong>OPTION 2: </strong><br />Put WordFence in Learning Mode, save a form, then take it out of learning mode. <br /><a href="'.get_option('siteurl').'/wp-admin/admin.php?page=WordfenceOptions" target="_blank">See Web <strong>Application Firewall Status</strong></a>
					 <br /><br />After you have done this, go back to your form and HIT SAVE AGAIN (even while the button is still spinning).
					 <br /><br /><button type="button" class=" button button-primary dismiss_nf_notice">Got it</a></p>
				 </div>');
				}
			}
    }
}

if( get_option( 'dismiss_nf_notice_wf_02' ) != true ) {
    add_action( 'admin_notices', 'add_nf_wf_notice_dismissible' );
}


add_action( 'wp_ajax_dismiss_nf_notice', 'dismiss_nf_notice' );
function dismiss_nf_notice(){
      update_option( 'dismiss_nf_notice_wf_02', true );
	  die();
}

function NEXForms_get_add_on_status($add_on_Id){
	
	global $wpdb;
	if(is_array($add_on_Id))
		$set_add_on_Id = $add_on_Id[0];
	
	$add_on_info = $wpdb->get_var('SELECT add_on_url FROM '.$wpdb->prefix.'wap_nex_forms_add_ons WHERE Id='.$set_add_on_Id);
	$output = '';
	
	$all_plugins = get_plugins();

		if (isset($all_plugins[$add_on_info.'/main.php']) || isset($all_plugins[$add_on_info.'/'.$add_on_info.'.php']))
			{
			if (is_plugin_active($add_on_info.'/main.php') || is_plugin_active($add_on_info.'/'.$add_on_info.'.php'))
				{
				$output .= '<span class="add-on-status activated">Active</span>';
				$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'active'),array('Id'=>$set_add_on_Id));
				}
			else
				{
				$output .= '<span class="add-on-status inactive">Inactive</span>';	
				$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'inactive'),array('Id'=>$set_add_on_Id));
				}
			}
		else
			{
			$output .= '<span class="add-on-status not_installed">Not Installed</span>';
			}
	
	
	
	//print_r($all_plugins);
	return $output;
	
}

function NEXForms_install_add_on($add_on_Id){
	
	global $wpdb;
	if(is_array($add_on_Id))
		$set_add_on_Id = $add_on_Id[0];
	
	$add_on_info = $wpdb->get_var('SELECT add_on_url FROM '.$wpdb->prefix.'wap_nex_forms_add_ons WHERE Id='.$set_add_on_Id);
	$output = '';
	
	$all_plugins = get_plugins();

		if (isset($all_plugins[$add_on_info.'/main.php']) || isset($all_plugins[$add_on_info.'/'.$add_on_info.'.php']))
			{
			if (is_plugin_active($add_on_info.'/main.php') || is_plugin_active($add_on_info.'/'.$add_on_info.'.php'))
				{
				$output .= '<button class="install_add_on nf_button aa_bg_main_btn" data-add-on-path="'.$add_on_info.'" data-do-action="deactivate" data-add-on-id="'.$set_add_on_Id.'">Deactivate</button>';
				}
			else
				{
				$output .= '<button class="install_add_on nf_button aa_bg_main_btn" data-add-on-path="'.$add_on_info.'" data-do-action="activate" data-add-on-id="'.$set_add_on_Id.'">Activate</button>';	
				}
			}
		else
			{
			$output .= '<button class="install_add_on nf_button aa_bg_main_btn" data-add-on-path="'.$add_on_info.'" data-do-action="install" data-add-on-id="'.$set_add_on_Id.'">Install</button>';
			}
	
	
	
	//print_r($all_plugins);
	return $output;
	
}

function NEXForms_get_add_on_description($add_on_Id){
	
	global $wpdb;
			
	if(is_array($add_on_Id))
		$set_add_on_Id = $add_on_Id[0];
	$description = $wpdb->get_var('SELECT description FROM '.$wpdb->prefix.'wap_nex_forms_add_ons WHERE Id='.$set_add_on_Id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	
	$description= wp_unslash($description);
	$description= str_replace('\"','',$description);
	$description= str_replace('/','',$description);
	$description = sanitize_text_field( $description );
	
	
	return $description;
	
}
function NEXForms_get_add_on_plans($add_on_Id){
	
	global  $wpdb;
			
	if(is_array($add_on_Id))
		$set_add_on_Id = $add_on_Id[0];
	$plans = $wpdb->get_var('SELECT plans FROM '.$wpdb->prefix.'wap_nex_forms_add_ons WHERE Id='.$set_add_on_Id); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

	
	return $plans;
	
}
function NEXForms_paypal_payment_status($payment_status){
		
		if(is_array($payment_status))
				$get_payment_status = $payment_status[0];
				
		if(	$get_payment_status=='pending')	
			return '<span class="payment-status txt-orange" title="Pending"><span class="fa fa-clock-o txt-blue-gray"></span></span>';
		if(	$get_payment_status=='failed')	
			return '<span class="payment-status txt-red" title="Failed"><span class="fa fa-close txt-red"></span> </span>';
		if(	$get_payment_status=='payed')	
			return '<span class="payment-status  txt-light-green" title="Payed"><span class="fa fa-check txt-light-green"></span> </span>';
	}
function NEXForms_time_elapsed_string($datetime, $full = false) {
			
			if(is_array($datetime))
				$set_date_time = $datetime[0];
				
			$tz = wp_timezone();
				
			$now = new DateTime("now", $tz);	
			$ago = new DateTime($set_date_time, $tz);
			
			$diff = $now->diff($ago);

		
			$diff->w = floor($diff->d / 7);
			$diff->d -= $diff->w * 7;
		
			$string = array(
				'y' => 'year',
				'm' => 'month',
				'w' => 'week',
				'd' => 'day',
				'h' => 'hour',
				'i' => 'minute',
				's' => 'second',
			);
			foreach ($string as $k => &$v) {
				if ($diff->$k) {
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
				} else {
					unset($string[$k]);
				}
			}
		
			if (!$full) $string = array_slice($string, 0, 1);
			
			return $string ? implode(', ', $string) . ' ago' : 'just now';
		}
$get_nf_functions = new NEXForms_Functions();

function NEXForms_clean_echo($content){
	$content = NEXForms_rgba2Hex($content);
	echo wp_kses( $content, NEXForms_allowed_tags());
} 
function NEXForms_clean_echo2($content){
	$content = NEXForms_rgba2Hex($content);
	echo wp_kses( $content, NEXForms_allowed_tags2());
}
function NEXForms_allowed_tags(){
	$default_attribs = array(
            'id' 			=> true,
            'class' 		=> true,
			'width' 		=> true,
			'height' 		=> true,
			'align' 		=> true,
			'valign' 		=> true,
            'title' 		=> true,
			'type' 			=> true,
            'style' 		=> true,
			'name' 			=> true,
			'value' 		=> true,
			'label' 		=> true,
			'val' 			=> true,
			'tabindex'		=> true,
			'role'			=> true,
			'onClick' 		=> true,
			'onBlur' 		=> true,
			'onChange' 		=> true,
			'click' 		=> true,
			'change' 		=> true,
			'keyup' 		=> true,
			'for' 			=> true,
			'multiple' 		=> true,
			'placeholder' 	=> true,
			'bgcolor' 		=> true,
			'minlength'		=> true,
			'maxlength'		=> true,	
			'selected'		=> true,
			'checked'		=> true,
			'disabled'		=> true,
            'data-*' 		=> true,
        );

	$allowed_tags = array(
		'div'           	=> $default_attribs,
		'span'          	=> $default_attribs,
		'p'             	=> $default_attribs,
		'a'             	=> array_merge( $default_attribs, array(
			'href' 			=> array(),
			'rel' 			=> array(),
			'target' 		=> array('_blank', '_top'),
		) ),
		'h1'             	=> $default_attribs,
		'h2'             	=> $default_attribs,
		'h3'             	=> $default_attribs,
		'h4'             	=> $default_attribs,
		'h5'             	=> $default_attribs,
		'h6'             	=> $default_attribs,
		'u'             	=> $default_attribs,
		'i'             	=> $default_attribs,
		'q'             	=> $default_attribs,
		'b'             	=> $default_attribs,
		'ul'            	=> $default_attribs,
		'ol'            	=> $default_attribs,
		'li'           	 	=> $default_attribs,
		'br'            	=> $default_attribs,
		'hr'            	=> $default_attribs,
		'strong'        	=> $default_attribs,
		'strike'        	=> $default_attribs,
		'caption'			=> $default_attribs,
		'blockquote'    	=> $default_attribs,
		'del'           	=> $default_attribs,
		'strike'        	=> $default_attribs,
		'input'        		=> $default_attribs,
		'select'        	=> $default_attribs,
		'option'        	=> $default_attribs,
		'optgroup'        	=> $default_attribs,
		'textarea'        	=> $default_attribs,
		'small'       	 	=> $default_attribs,
		'label'        		=> $default_attribs,
		'em'            	=> $default_attribs,
		'code'          	=> $default_attribs,
		'canvas'			=> $default_attribs,
		'nav'				=> $default_attribs,
		'iframe'          			=> array_merge( $default_attribs, array(
			'src' 					=> array(),
			'allow' 				=> array(),
			'allowfullscreen' 		=> array(),
			'allowpaymentrequest' 	=> array(),
			'height' 				=> array(),
			'loading' 				=> array(),
			'name' 					=> array(),
			'referrerpolicy' 		=> array(),
			'sandbox' 				=> array(),
			'srcdoc' 				=> array(),
			'width' 				=> array(),
		) ),
		'img'          		=> array_merge( $default_attribs, array(
			'src' 			=> array(),
			'alt' 			=> array(),
			'valign' 		=> array(),
			'halign' 		=> array(),
		) ),
		'table'          	=> array_merge( $default_attribs, array(
			'border' 		=> array(),
			'bordercolor' 	=> array(),
			'cellspacing' 	=> array(),
			'cellpadding' 	=> array(),
			'background' 	=> array(),
		) ),
		'tbody'        		=> $default_attribs,
		'thead'        		=> $default_attribs,
		'tfoot'        		=> $default_attribs,
		'th'        		=> $default_attribs,
		'tr'        		=> $default_attribs,
		'td'          		=> array_merge( $default_attribs, array(
			'colspan' 		=> array(),
			'rowspan' 		=> array(),
		) ),
		'button'        	=> $default_attribs,
		'style'         	=> $default_attribs,
		'script'         	=> $default_attribs,
		'body'         		=> $default_attribs,
		'head'         		=> $default_attribs,
		'form'          	=> array_merge( $default_attribs, array(
			'name' 			=> array(),
			'method' 		=> array(),
			'enctype' 		=> array(),
			'action' 		=> array(),
		) ),
		'link'          	=> array_merge( $default_attribs, array(
			'rel' 			=> array(),
			'href' 			=> array(),
		) ),
		'video'          	=> array_merge( $default_attribs, array(
			'autoplay' 		=> array(),
			'controls' 		=> array(),
			'loop' 			=> array(),
			'muted' 		=> array(),
			'poster' 		=> array(),
			'preload' 		=> array(),
			'src' 			=> array(),
		) ),
		'audio'          	=> array_merge( $default_attribs, array(
			'autoplay' 		=> array(),
			'controls' 		=> array(),
			'loop' 			=> array(),
			'muted' 		=> array(),
			'preload' 		=> array(),
			'src' 			=> array(),
		) ),
		'source'          	=> array_merge( $default_attribs, array(
			'srcset' 		=> array(),
			'sizes' 		=> array(),
			'src' 			=> array(),
			'media' 		=> array(),
		) ),
	);
	return $allowed_tags;
}



function NEXForms_allowed_tags2(){
	$default_attribs = array(
            'id' 			=> true,
            'class' 		=> true,
			'width' 		=> true,
			'height' 		=> true,
			'align' 		=> true,
			'valign' 		=> true,
            'title' 		=> true,
			'type' 			=> true,
            'style' 		=> true,
			'name' 			=> true,
			'value' 		=> true,
			'label' 		=> true,
			'val' 			=> true,
			'tabindex'		=> true,
			'role'			=> true,
			'onClick' 		=> true,
			'onBlur' 		=> true,
			'onChange' 		=> true,
			'click' 		=> true,
			'change' 		=> true,
			'keyup' 		=> true,
			'for' 			=> true,
			'multiple' 		=> true,
			'placeholder' 	=> true,
			'bgcolor' 		=> true,
			'minlength'		=> true,
			'maxlength'		=> true,	
			'selected'		=> true,
			'checked'		=> true,
			'disabled'		=> true,
            'data-*' 		=> true,
        );

	$allowed_tags = array(
		'div'           	=> $default_attribs,
		'span'          	=> $default_attribs,
		'p'             	=> $default_attribs,
		'a'             	=> array_merge( $default_attribs, array(
			'href' 			=> array(),
			'rel' 			=> array(),
			'target' 		=> array('_blank', '_top'),
		) ),
		'h1'             	=> $default_attribs,
		'h2'             	=> $default_attribs,
		'h3'             	=> $default_attribs,
		'h4'             	=> $default_attribs,
		'h5'             	=> $default_attribs,
		'h6'             	=> $default_attribs,
		'u'             	=> $default_attribs,
		'i'             	=> $default_attribs,
		'q'             	=> $default_attribs,
		'b'             	=> $default_attribs,
		'ul'            	=> $default_attribs,
		'ol'            	=> $default_attribs,
		'li'           	 	=> $default_attribs,
		'br'            	=> $default_attribs,
		'hr'            	=> $default_attribs,
		'strong'        	=> $default_attribs,
		'strike'        	=> $default_attribs,
		'caption'			=> $default_attribs,
		'blockquote'    	=> $default_attribs,
		'del'           	=> $default_attribs,
		'strike'        	=> $default_attribs,
		'input'        		=> $default_attribs,
		'select'        	=> $default_attribs,
		'option'        	=> $default_attribs,
		'optgroup'        	=> $default_attribs,
		'textarea'        	=> $default_attribs,
		'small'       	 	=> $default_attribs,
		'label'        		=> $default_attribs,
		'em'            	=> $default_attribs,
		'code'          	=> $default_attribs,
		'canvas'			=> $default_attribs,
		'nav'				=> $default_attribs,
		'iframe'          			=> array_merge( $default_attribs, array(
			'src' 					=> array(),
			'allow' 				=> array(),
			'allowfullscreen' 		=> array(),
			'allowpaymentrequest' 	=> array(),
			'height' 				=> array(),
			'loading' 				=> array(),
			'name' 					=> array(),
			'referrerpolicy' 		=> array(),
			'sandbox' 				=> array(),
			'srcdoc' 				=> array(),
			'width' 				=> array(),
		) ),
		'img'          		=> array_merge( $default_attribs, array(
			'src' 			=> array(),
			'alt' 			=> array(),
			'valign' 		=> array(),
			'halign' 		=> array(),
		) ),
		'table'          	=> array_merge( $default_attribs, array(
			'border' 		=> array(),
			'bordercolor' 	=> array(),
			'cellspacing' 	=> array(),
			'cellpadding' 	=> array(),
			'background' 	=> array(),
		) ),
		'tbody'        		=> $default_attribs,
		'thead'        		=> $default_attribs,
		'tfoot'        		=> $default_attribs,
		'th'        		=> $default_attribs,
		'tr'        		=> $default_attribs,
		'td'          		=> array_merge( $default_attribs, array(
			'colspan' 		=> array(),
			'rowspan' 		=> array(),
		) ),
		'button'        	=> $default_attribs,
		'style'         	=> $default_attribs,
		'body'         		=> $default_attribs,
		'head'         		=> $default_attribs,
		'form'          	=> array_merge( $default_attribs, array(
			'name' 			=> array(),
			'method' 		=> array(),
			'enctype' 		=> array(),
			'action' 		=> array(),
		) ),
		'link'          	=> array_merge( $default_attribs, array(
			'rel' 			=> array(),
			'href' 			=> array(),
		) ),
		'video'          	=> array_merge( $default_attribs, array(
			'autoplay' 		=> array(),
			'controls' 		=> array(),
			'loop' 			=> array(),
			'muted' 		=> array(),
			'poster' 		=> array(),
			'preload' 		=> array(),
			'src' 			=> array(),
		) ),
		'audio'          	=> array_merge( $default_attribs, array(
			'autoplay' 		=> array(),
			'controls' 		=> array(),
			'loop' 			=> array(),
			'muted' 		=> array(),
			'preload' 		=> array(),
			'src' 			=> array(),
		) ),
		'source'          	=> array_merge( $default_attribs, array(
			'srcset' 		=> array(),
			'sizes' 		=> array(),
			'src' 			=> array(),
			'media' 		=> array(),
		) ),
	);
	return $allowed_tags;
}
function NEXForms_add_ons_array(){
	
	$add_ons = array(
		'add_on_1'=>array(
			'title'			=>  'PayPal Pro',
			'description'	=>	'Enable secure online payments via PayPal with itemized checkout and email triggers based on payment status.',
			'price'			=>	'29',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-paypal-advanced',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_2'=>array(
			'title'			=>  'PDF Creator',
			'description'	=>	'Generate custom PDFs from submitted form data and attach them to admin and user emails automatically.',
			'price'			=>	'29',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-export-to-pdf',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_3'=>array(
			'title'			=>  'Form to POST / PAGE',
			'description'	=>	'Automatically create WordPress posts or pages from form submissions, with support for featured images and smart data tag content mapping.',
			'price'			=>	'29',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-form-to-post7',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_4'=>array(
			'title'			=>  'Digital / E-Signatures',
			'description'	=>	'Add digital signature fields to your forms and include captured signatures in emails and generated PDFs.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-digital-signatures7',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_5'=>array(
			'title'			=>  'Zapier Integration',
			'description'	=>	'Connect NEX-Forms to over 8,000 apps with Zapier for powerful automation and seamless data workflows.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-zapier',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_6'=>array(
			'title'			=>  'Multi-Page Forms',
			'description'	=>	'Create multi-page forms with the ability to pass submitted data seamlessly from one form to the next.',
			'price'			=>	'29',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-multi-page-forms',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_7'=>array(
			'title'			=>  'Form Themes/Color Schemes',
			'description'	=>	'Easily match your form’s design to your site with built-in themes: Bootstrap, Material Design, Neumorphism, jQuery UI, and Classic. Includes 44 preset color schemes for instant styling.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-themes-add-on7',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_8'=>array(
			'title'			=>  'Super Selection',
			'description'	=>	'Create fully customizable radio buttons, checkboxes, dropdowns, and spinners using 2000+ icons. Set unique icons and colors for selected and unselected states.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-super-select',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_9'=>array(
			'title'			=>  'Conditional Content Blocks',
			'description'	=>	'Dynamically show or hide content in emails and PDFs based on user input. Perfect for personalizing messages using submitted form data.',
			'price'			=>	'29',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-conditional-content-blocks',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_10'=>array(
			'title'			=>  'Shortcode Processor',
			'description'	=>	'Execute your own or third-party shortcodes directly within forms for added functionality and seamless integration.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-shortcode-processor',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_11'=>array(
			'title'			=>  'MailChimp',
			'description'	=>	'Automatically add new subscribers to your MailChimp lists directly from NEX-Forms submissions.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-mail-chimp-add-on',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_12'=>array(
			'title'			=>  'Mailster',
			'description'	=>	'Automatically add new subscribers to your Mailster lists directly from NEX-Forms submissions.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-mailster',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_13'=>array(
			'title'			=>  'MailPoet',
			'description'	=>	'Automatically add new subscribers to your MailPoet lists directly from NEX-Forms submissions.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-mail-poet',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			),
		'add_on_14'=>array(
			'title'			=>  'GetResponse',
			'description'	=>	'Automatically add new subscribers to your GetResponse lists directly from NEX-Forms submissions.',
			'price'			=>	'19',
			'status'		=>	'not installed',
			'add_on_url'	=>	'nex-forms-getresponse-add-on7',
			'version'		=>	'9.0',
			'plans'			=>	'Basic, Plus, Pro, Elite'
			)
		);
	return array_reverse($add_ons);
}

function NEXForms_set_add_ons(){
	
	global $wpdb;
	
	$get_add_ons = NEXForms_add_ons_array();
	foreach($get_add_ons as $key=>$add_on)
		{
		$get_add_on = $wpdb->get_var($wpdb->prepare('SELECT title FROM `'. $wpdb->prefix .'wap_nex_forms_add_ons` WHERE title=%s',$add_on['title']));
		
		if(!$get_add_on)
			$wpdb->insert ( $wpdb->prefix . 'wap_nex_forms_add_ons', $add_on);  // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		//else
		//	$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', $add_on,array('title'=>$add_on['title']));  // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}	
}

function NEXForms_safe_user_functions(){
	$whitelist_func = array(
	'NEXForms_starred',
	'NEXForms_get_attachment',
	'NEXForms_entry_status',
	'NEXForms_get_title3',
	'NEXForms_time_elapsed_string',
	'NEXForms_get_title',
	'NEXForms_download_file',
	'link_form_title_2',
	'get_form_shortcode',
	'get_total_entries_3',
	'link_form_title',
	'duplicate_record',
	'print_export_form_link',
	'NEXForms_get_entry_data_preview',
	'link_report_title',
	'get_total_report_records',
	'report_last_update',
	'quick_report_csv',
	'quick_report_pdf',
	'link_report_title2',
	'get_total_entries',
	'NEXForms_paypal_payment_status',
	'NEXForms_get_add_on_status',
	'NEXForms_install_add_on',
	'NEXForms_get_add_on_description',
	'NEXForms_get_add_on_plans',
	'get_add_on_version',
	'get_add_on_update',
	);
	return $whitelist_func;
}

function NEXForms_isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

add_action('wp_ajax_nexforms_install_addon', 'nexforms_install_addon');

function nexforms_install_addon() {
    if (!current_user_can('install_plugins')) {
        wp_send_json_error(array('message' => 'Permission denied.'));
    }
	
	if ( !wp_verify_nonce( $_REQUEST['nex_forms_wpnonce'], 'nf_admin_dashboard_actions' ) ) {
				wp_die();
			}
	
	
	
	
	if(!get_option('nf_activated'))
		{
		if(function_exists('nf_fs'))
			{
			if ( !nf_fs()->can_use_premium_code() )
				{
				wp_send_json_error(array('message' => 'no_plan'));	
				wp_die();
				}
			}
		}
	else
		{
		$dashboard = new NEXForms_dashboard();
		$dashboard->dashboard_checkout();
		$supported_until = $license_info['supported_until'];
		$supported_date = new DateTime($supported_until);
		$now = new DateTime();
		if ($supported_date < $now)	
			{
			wp_send_json_error(array('message' => 'no_plan'));	
			wp_die();
			}
		}
	
	global $wpdb;
	
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    $addon_url = esc_url_raw($_POST['addon_url']);
	$plugin_file_path = esc_html(sanitize_text_field($_POST['plugin_file']));
	$add_on_id = esc_html(sanitize_text_field($_POST['add_on_id']));
	
	$do_action = esc_html(sanitize_text_field($_POST['do_action']));
	
    if (empty($addon_url)) {
        wp_send_json_error(array('message' => 'No URL provided.'));
    }
	
	if($do_action=='deactivate')
		{
			deactivate_plugins($plugin_file_path.'/main.php');
			deactivate_plugins($plugin_file_path.'/'.$plugin_file_path.'.php');
			$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'inactive'),array('Id'=>$add_on_id));
			wp_send_json_error(array('message' => "Add-on deactivated."));
		
		}
	if($do_action=='activate')
		{
		$activate = activate_plugin($plugin_file_path.'/main.php');
		$activate2 = activate_plugin($plugin_file_path.'/'.$plugin_file_path.'.php');
		$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'active'),array('Id'=>$add_on_id));
		wp_send_json_error(array('message' => "Add-on Activated."));
			
		}
	if($do_action=='install')
		{
		$skin = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader($skin);
		$result = $upgrader->install($addon_url);
	
		if (is_wp_error($result)) {
			wp_send_json_error(array('message' => $result->get_error_message()));
		}
	
		// Get installed plugin file path (from the last_result)
		if (!empty($upgrader->plugin_info())) {
			$plugin_file = $upgrader->plugin_info(); // e.g., 'nexforms-addon/nexforms-addon.php'
			
			
			// Activate the plugin
			$activate = activate_plugin($plugin_file);
	
			if (is_wp_error($activate)) {
				$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'inactive'),array('Id'=>$add_on_id));  // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				wp_send_json_error(array('message' => 'Installed but activation failed: ' . $activate->get_error_message()));
			} else {
				$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'active'),array('Id'=>$add_on_id));  // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				wp_send_json_success(array('message' => 'Add-on installed and activated successfully.'));
			}
			
			
	
			
			
			$wpdb->update ( $wpdb->prefix . 'wap_nex_forms_add_ons', array('status'=>'inactive'),array('Id'=>$add_on_id));
			wp_send_json_error(array('message' => 'Add-on installed but could not determine plugin file to activate.'));
			}
		}
}
?>