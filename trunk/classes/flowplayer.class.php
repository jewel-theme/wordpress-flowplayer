<?PHP

/**
 * FlowPlayer for Wordpress
 * ©2008,2009 David Busby
 * @see license.tct GPLv3
 */
class flowplayer {
	
	function setup(){
		global $post;
		
		flowplayer::plugin_url();
		flowplayer::commercial_url();
		flowplayer::gpl_url();
		flowplayer::player();
		
		if(!defined('FLOWPLAYER_META')){
			define('FLOWPLAYER_META','flowplayer_meta');
		}
		
		if(!defined('FLOWPLAYER_DATA')){
			define('FLOWPLAYER_DATA', get_post_meta($post->ID, FLOWPLAYER_META));
		}
	}
	
	function data_parse($data){
		$data = explode('|',$data);
		$ret = array(
			'media' => $data[0],
			'width' => $data[1],
			'height' => $data[2]
		);
		return $ret;
	}
	function commercial_url(){
		if(!defined('FLOWPLAYER_COMMERCIAL')){
			define('FLOWPLAYER_COMMERCIAL',flowplayer::plugin_url().'/flowplayer/commercial/flowplayer.commercial-3.1.1.swf');
		}
		return FLOWPLAYER_COMMERCIAL;
	}
	
	function gpl_url(){
		if(!defined('FLOWPLAYER_GPL')){
			define('FLOWPLAYER_GPL',flowplayer::plugin_url().'/flowplayer/gpl/flowplayer-3.1.1.swf');
		}
		
		return FLOWPLAYER_GPL;
	}
	
	function player(){
		if(!defined('FLOWPLAYER_URL')){
			if(flowplayer::_getkey()){
				define('FLOWPLAYER_URL',flowplayer::commercial_url());
			} else {
				define('FLOWPLAYER_URL',flowplayer::gpl_url());
			}
		}
		return FLOWPLAYER_URL;
	}
	
	function plugin_url(){
		if(!defined('PLUGIN_URL')){
			define('PLUGIN_URL',get_option('siteurl').'/wp-content/plugins/word-press-flow-player');
		}	
		return PLUGIN_URL;
	}
	
	function _getbackgroundColor(){ return get_option('flowplayer_backgroundColor'); }		
	function _getcanvas(){ return get_option('flowplayer_canvas'); }
	function _getsliderColor(){ return get_option('flowplayer_sliderColor'); }
	function _getbuttonColor(){ return get_option('flowplayer_buttonColor'); }
	function _getbuttonOverColor(){ return get_option('flowplayer_buttonOverColor'); }
	function _getdurationColor(){ return get_option('flowplayer_durationColor'); }
	function _gettimeColor(){ return get_option('flowplayer_timeColor'); }
	function _getprogressColor(){ return get_option('flowplayer_progressColor'); }
	function _getbufferColor(){ return get_option('flowplayer_bufferColor'); }
	function _getautobuffer(){return get_option('flowplayer_autobuffer');}
	function _getautoplay(){return get_option('flowplayer_autoplay');}
	function _getkey(){	return get_option('flowplayer_key');}
	function _getlogo(){ return get_option('flowplayer_logo');}
	function _getlogolink(){ return get_option('flowplayer_logolink');}
	
	function _setbackgroundColor($val){ update_option('flowplayer_backgroundColor',$val); }		
	function _setcanvas($val){ update_option('flowplayer_canvas',$val); }
	function _setsliderColor($val){ update_option('flowplayer_sliderColor',$val); }
	function _setbuttonColor($val){ update_option('flowplayer_buttonColor',$val); }
	function _setbuttonOverColor($val){ update_option('flowplayer_buttonOverColor',$val); }
	function _setdurationColor($val){ update_option('flowplayer_durationColor',$val); }
	function _settimeColor($val){ update_option('flowplayer_timeColor',$val); }
	function _setprogressColor($val){ update_option('flowplayer_progressColor',$val); }
	function _setbufferColor($val){ update_option('flowplayer_bufferColor',$val); }
	function _setkey($val){	update_option('flowplayer_key',$val);}
	function _setautoplay($val){update_option('flowplayer_autoplay', $val);}
	function _setautobuffer($val){update_option('flowplayer_autobuffer',$val);}
	function _setlogo($val){update_option('flowplayer_logo',$val);}
	function _setlogolink($val){update_option('flowplayer_logolink',$val);}
	
	function get_nonce(){
		if(!defined('FLOWPLAYER_NONCE')){
			define('FLOWPLAYER_NONCE',wp_create_nonce( plugin_basename(__FILE__) ));
			define('FLOWPLAYER_NONCE_FILE', plugin_basename(__FILE__));
		}
		
		return FLOWPLAYER_NONCE;
	}
	
	function player_head(){
		if(!defined('FLOWPLAYER_HEAD')){
			$html = "\n<!-- Saiweb.co.uk Flowplayer For Wordpress Javascript Start -->\n";
			$html .= "\n".'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>';
			$html .= "\n".'<script type="text/javascript">WPFP = jQuery.noConflict(); $ = WPFP; </script>';
			$html .= '<script type="text/javascript" src="'.flowplayer::plugin_url().'/flowplayer/flowplayer-3.1.1.min.js"></script>';
 			$html .= "\n<!-- Saiweb.co.uk Flowplayer For Wordpress Javascript END -->\n";
			define('FLOWPLAYER_HEAD', $html);	
		}		
		if(!is_admin()){return FLOWPLAYER_HEAD;}
		else {return '';}
	}
	
	function admin_head(){
		if(!defined('FLOWPLAYER_ADMIN_HEAD')){
			$html = "\n<!-- Saiweb.co.uk Flowplayer For Wordpress ADMIN Javascript Start -->\n";
 			//$html .= '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>';	
 			$html .= "\n".'<script type="text/javascript">WPFP = jQuery.noConflict(); $ = WPFP; </script>';
 			$html .= "\n".'<script type="text/javascript" src="'.flowplayer::plugin_url().'/js/farbtastic/farbtastic.js"></script>';
 			$html .= "\n".'<script src="http://svn.saiweb.co.uk/branches/jquery_plugin/tags/latest/jquery.saiweb.min.js" type="text/javascript"></script>';
 			$html .= "\n".'<link rel="stylesheet" href="'.flowplayer::plugin_url().'/js/farbtastic/farbtastic.css" type="text/css" />';
 			$html .= "\n<!-- Saiweb.co.uk Flowplayer For Wordpress ADMIN Javascript END -->\n";
 			$html .= flowplayer::player_head();
			define('FLOWPLAYER_ADMIN_HEAD',$html);
		}
		
		return FLOWPLAYER_ADMIN_HEAD;
	}
	
	/**
 	* Output 'selected' bool options based on arg passed
 	* @var string true / false
 	* @return string HTML
 	*/
	function bool_select($current) {
		switch($current) {
		 		case "true":
		 			$html = '<option selected value="true">true</option><option value="false">false</option>';
		 		break;
		 		case "false":
		 			$html = '<option value="true" >true</option><option selected value="false">false</option>';
		 		break;
		 		default:
		 			$html = '<option value="true">true</option><option selected value="false">false</option>';
		 		break;
		 	}
		 return $html;
	}
	
	function flowplayer_colours(){
		$html ='<ul>
		<li><input disabled type="radio" name="tgt" value="flowplayer_backgroundColor" checked /> controlbar</li>		
		<li><input disabled type="radio" name="tgt" value="flowplayer_canvas" /> canvas</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_sliderColor" /> sliders</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_buttonColor" /> buttons</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_buttonOverColor" /> mouseover</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_durationColor" /> total time</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_timeColor" /> time</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_progressColor" /> progress</li>
		<li><input disabled type="radio" name="tgt" value="flowplayer_bufferColor" /> buffer</li>
		</ul>
		';
		
		$html .= 
			'
			<input type="hidden" name="flowplayer_backgroundColor" value="'.flowplayer::_getbackgroundColor().'" />		
			<input type="hidden" name="flowplayer_canvas" value="'.flowplayer::_getcanvas().'" />
			<input type="hidden" name="flowplayer_sliderColor" value="'.flowplayer::_getsliderColor().'" />
			<input type="hidden" name="flowplayer_buttonColor" value="'.flowplayer::_getbuttonColor().'" />
			<input type="hidden" name="flowplayer_buttonOverColor" value="'.flowplayer::_getbuttonOverColor().'" />
			<input type="hidden" name="flowplayer_durationColor" value="'.flowplayer::_getdurationColor().'" />
			<input type="hidden" name="flowplayer_timeColor" value="'.flowplayer::_gettimeColor().'" />
			<input type="hidden" name="flowplayer_progressColor" value="'.flowplayer::_getprogressColor().'" />
			<input type="hidden" name="flowplayer_bufferColor" value="'.flowplayer::_getbufferColor().'" />
		';
		return $html;
	}
	
	function flowplayer_settings(){
		$html = '
					<script language="Javascript" type="text/javascript">
	WPFP(document).ready(function() {
		//load player
		$f("player", "'.(flowplayer::_getkey()?flowplayer::commercial_url():flowplayer::gpl_url()).'", {
				plugins: {
  					 controls: {    					
      					buttonOverColor: \''.flowplayer::_getbuttonOverColor().'\',
      					sliderColor: \''.flowplayer::_getsliderColor().'\',
      					bufferColor: \''.flowplayer::_getbufferColor().'\',
      					sliderGradient: \'none\',
      					progressGradient: \'medium\',
      					durationColor: \''.flowplayer::_getdurationColor().'\',
      					progressColor: \''.flowplayer::_getprogressColor().'\',
      					backgroundColor: \''.flowplayer::_getbackgroundColor().'\',
      					timeColor: \''.flowplayer::_gettimeColor().'\',
      					buttonColor: \''.flowplayer::_getbuttonColor().'\',
      					backgroundGradient: \'none\',
      					bufferGradient: \'none\',
   						opacity:1.0
   						}
				},
				clip: {
					url:\'http://saiweb.co.uk/wp-content/videos/wpfp_config_demo.mp4\',
					autoPlay: '.(flowplayer::_getautoplay()=='true'?'true':'false').',
       				autoBuffering: '.(flowplayer::_getautobuffer()=='true'?'true':'false').'
				},
				'.(flowplayer::_getkey()?'key:\''.flowplayer::_getkey().'\',':'');
if($fp->conf['logoenable'] == 'true'){
	$html .= '
		logo: {  
        url: \'http://'.$fp->conf['logo'].'\',  
        fullscreenOnly: '.$fp->conf['fullscreenonly'].',  
        displayTime: 0,
        linkUrl: \'http://'.$fp->conf['logolink'].'\' 
    },';
}
$html .= '
				canvas: {
					backgroundColor:\''.flowplayer::_getcanvas().'\'
				},
				onLoad: function() {
					$(":input[name=tgt]").removeAttr("disabled");		
				},
				onUnload: function() {
					$(":input[name=tgt]").attr("disabled", true);		
				}
			});
		//colour picker call back to api and hidden vars		
		WPFP(\'#colourpicker\').farbtastic(function(color){
			var tgt = $(":input[name=tgt]:checked").val();
			tgtArr = tgt.split(\'_\');
			//set to hidden input
			$(":input[name="+tgt+"]").val(color);
			var player = $f("player");
				
			if (player.isLoaded()) {						

			// adjust canvas bgcolor. uses undocumented API call. not stabilized yet
			if (tgtArr[1] == \'canvas\') {					
				player._api().fp_css("canvas", {backgroundColor:color});
				
			// adjust controlbar coloring
			} else {
				window.canvasColor = color;
				player.getControls().css(tgtArr[1], color);
			}			
			
		} else {
			player.load();	
		}			
		});
	});
</script>
						<div class="wrap">
						<form id="wpfp_options" method="post">
						<div id="icon-options-general" class="icon32"><br></div>
						<h2><a href="http://www.saiweb.co.uk">Saiweb</a> Flowplayer for Wordpress</h2>
						';
						if(!flowplayer::_getkey()){
							$html .= '
						<h3>Like this plugin?</h3>
							A lot of development time and effort went into Flowplayer and this plugin, you can help support further development by purchasing a comercial license for flowplayer.
						<h3><a href="http://flowplayer.org/download/index.html?aff=100&src=saiweb_plugin&domain='.$_SERVER['SERVER_NAME'].'" target="_blank">Get a commercial license now!</a></h3>
						';
						
						}
						$html .= '<h3>Please set your default player options below</h3>
						<table>
							<tr>
								<td>AutoPlay: </td>
								<td>
		 							<select name="flowplayer_autoplay">
										'.flowplayer::bool_select(flowplayer::_getautoplay()).'
		 							</select>
		 							
		 						</td>
							</tr>
							<tr>
								<td>Commercial License Key: </td>
								<td>
									<input type="text" size="20" name="flowplayer_key" id="flowplayer_key" value="'.flowplayer::_getkey().'" />	
									(Required for certain features i.e. custom logo)
								</td>
							</tr>
							<tr>
								<td>Logo URL: </td>
								<td>
									<input type="text" size="20" name="flowplayer_logo" id="flowplayer_logo" value="'.flowplayer::_getlogo().'" />	
									(only works with valid license key)
								</td>
							</tr>
							<tr>
								<td>Logo Link: </td>
								<td>
									<input type="text" size="20" name="flowplayer_logolink" id="flowplayer_logolink" value="'.flowplayer::_getlogolink().'" />	
									(only works with valid license key)
								</td>
							</tr>
							<tr>
								<td>Auto Buffering:</td>
								<td><select name="flowplayer_autobuffer">'.flowplayer::bool_select(flowplayer::_getautobuffer()).'</select></td>
							</tr>
							<tr>
								<td><div id="colourpicker"></div></td>
								<td>
									'.flowplayer::flowplayer_colours().'
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<input type="submit" name="submit" class="button-primary" value="Save Changes" />
								</td>
							</tr>
						</table>
						<input type="hidden" name="flowplayer_noncename" id="flowplayer_noncename" value="'.flowplayer::get_nonce().'" />
						</form>
						<div id="player" style="width:300px;height:200px;"></div>
					</div>


';
		return $html;
	}
	
	 /** 
	  * Salt function
	  * @see http://trac.saiweb.co.uk/saiweb/ticket/2
	  * @return string salt
	  */
	function _salt() {
        $salt = substr(md5(uniqid(rand(), true)), 0, 10);    
        return $salt;
	}
	
	function legacy_hook($content){
		
		$regex = '/\[FLOWPLAYER=([a-z0-9\:\.\-\&\_\/]+)\,([0-9]+)\,([0-9]+)\]/i';
		$matches = array();
	
		preg_match_all($regex, $content, $matches);
		
		if($matches[0][0] != '') {
			foreach($matches[0] as $key => $data) {
				$content = str_replace($matches[0][$key], flowplayer::build_player($matches[2][$key], $matches[3][$key], $matches[1][$key]),$content);
			}	
		}
		
		return $content;
	}
	
	function build_player($width,$height,$media){
			/*
			 * Fix #2 
			 * @see http://trac.saiweb.co.uk/saiweb/ticket/2
			 */
			$hash = md5($media.flowplayer::_salt());
			$html = '<div id="saiweb_'.$hash.'" style="width:'.$width.'px; height:'.$height.'px;"></div>';
			$html .= '<script language="Javascript" type="text/javascript">
	WPFP(document).ready(function() {
		//load player
		$f("saiweb_'.$hash.'", "'.(flowplayer::_getkey()?flowplayer::commercial_url():flowplayer::gpl_url()).'", {
				plugins: {
  					 controls: {    					
      					buttonOverColor: \''.flowplayer::_getbuttonOverColor().'\',
      					sliderColor: \''.flowplayer::_getsliderColor().'\',
      					bufferColor: \''.flowplayer::_getbufferColor().'\',
      					sliderGradient: \'none\',
      					progressGradient: \'medium\',
      					durationColor: \''.flowplayer::_getdurationColor().'\',
      					progressColor: \''.flowplayer::_getprogressColor().'\',
      					backgroundColor: \''.flowplayer::_getbackgroundColor().'\',
      					timeColor: \''.flowplayer::_gettimeColor().'\',
      					buttonColor: \''.flowplayer::_getbuttonColor().'\',
      					backgroundGradient: \'none\',
      					bufferGradient: \'none\',
   						opacity:1.0
   						}
				},
				clip: {
					url:\''.$media.'\',
					autoPlay: '.(flowplayer::_getautoplay()=='true'?'true':'false').',
       				autoBuffering: '.(flowplayer::_getautobuffer()=='true'?'true':'false').'
				},
				'.(flowplayer::_getkey()?'key:\''.flowplayer::_getkey().'\',':'');
if($fp->conf['logoenable'] == 'true'){
	$html .= '
		logo: {  
        url: \'http://'.$fp->conf['logo'].'\',  
        fullscreenOnly: '.$fp->conf['fullscreenonly'].',  
        displayTime: 0,
        linkUrl: \'http://'.$fp->conf['logolink'].'\' 
    },';
}
$html .= '
				canvas: {
					backgroundColor:\''.flowplayer::_getcanvas().'\'
				}})
			});</script>
				';
				
		return $html;	
	}
}

?>