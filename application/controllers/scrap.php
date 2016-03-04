<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrap extends CI_Controller {



	function __construct(){

		parent::__construct();

		$this->layout->auto_render=false;	

		$this->layout->layout='default';		

	}

	/*function curl_check(){
		echo "ok";
	$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://appadmin.synpat.com/Users/potential_particpant',
				CURLOPT_USERAGENT => 'Send Request for create demo portfolio'		,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => array(
					'i' => 'asdasdasdasd',
					'file' => 'dasdsadsadsadsadsad'
				)				
			));
			$resp = curl_exec($curl);
			print_r($resp);
}*/
	
	function fetchOther(){
		// Set your return content type
		/*
		header('Content-type: application/xml');

		// Website url to open
		$url = 'https://search.rpxcorp.com/lit/txndce-211022-graftech-international-holdings-v-research-in-motion#simple1';

		// Get that website's content
		$handle = fopen($url, "r");
		// If there is something, read and return
		if ($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				echo $buffer;
			}
			fclose($handle);
		}
		*/
		header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

		$url = (isset($_GET['url'])) ? $_GET['url'] : false;
		if(!$url) exit;

		$referer = (isset($_SERVER['HTTP_REFERER'])) ? strtolower($_SERVER['HTTP_REFERER']) : false;
		$is_allowed = $referer && strpos($referer, strtolower($_SERVER['SERVER_NAME'])) !== false;

		$string = ($is_allowed) ? utf8_encode(file_get_contents($url)) : 'You are not allowed to use this proxy!';
		$json = json_encode($string);
		$callback = (isset($_GET['callback'])) ? $_GET['callback'] : false;
		if($callback){
			$jsonp = "$callback($json)";
			header('Content-Type: application/html');
			echo $jsonp;
			exit;
		}
		echo $json;
		
		die;
	}

	function index(){
		/*
	?>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->config->base_url()?>public/jquery.ajax-cross-origin.min.js"></script>
<script>
baseUrl = "https://search.rpxcorp.com/lit/txndce-211022-graftech-international-holdings-v-research-in-motion#simple1";
__caseName  = "";
__caseNumber  = "";
__filed = "";
__closed = "";
__lastDocket = "";
__pacer = "";
__leadAttroney = "";
__marketSector="";
__casetype="";
_liti = "";
_dd = "";
_table = {};
var o = {};
		var parser = document.createElement('a');
		parser.href = baseUrl;
		$(document).ready(function(){
		$.ajax({
			crossOrigin: true,
			url: baseUrl,
			context: {},
			success: function(data) {
				_dd = data;
				__caseName = jQuery(data).find("#mixpanel_object_name_holder").html();
				__caseNumber = jQuery(data).find('ul.subsidiaries').find('li').eq(0).html();
				__filed = jQuery(data).find('ul.subsidiaries').find('li').eq(1).html();
				ff = __filed.split('Filed:');
				__filed = ff[1];
				__closed = jQuery(data).find('ul.subsidiaries').find('li').eq(2).html();
				__lastDocket = jQuery(data).find('ul.subsidiaries').find('li').eq(3).html();
				__pacer = jQuery(data).find('ul.subsidiaries').find('li').eq(4).find('a').attr('href');
				__marketSector = jQuery(data).find('ul.case-details').find('li').eq(1).html();
				__marketSector = jQuery.trim(__marketSector.substr(__marketSector.indexOf('</div')+6));
				__casetype= jQuery(data).find('ul.case-details').find('li').eq(0).find('.red').html();
				jQuery(data).find("div#plaintiff_container").find('ul').find('div.counsel-content').find('div.counsel-party').each(function(){
					_mainParent = jQuery(this).html();
					_splitElement = jQuery(this).html().split('<br>');
					if(_splitElement.length>0){
						for(i=0;i<_splitElement.length;i++){
							if(_splitElement[i].indexOf('Lead Attorney')>=0){
								__leadAttroney = _mainParent.replace(/&nbsp;/g, ' ').replace(/<br.*?>/g, '\n');
							}
						}
					}
				});
				 
			}
		}).done(function(){
			_url = parser.protocol+parser.hostname+parser.pathname+"/related_cases";
			$.ajax({
				crossOrigin: true,
				url: _url,
				context: {},
				success: function(data) {
					_liti = data;
					_i=1;
					jQuery(data).find('ul.tabs-content').find('li').each(function(){
						_table[_i] = [];
						jQuery(this).find('div.table-expand').find('table').find('tbody').find('tr').each(function(){							
							data = [];
							jQuery(this).find('td').each(function(){
								data.push(jQuery(this).html());
							});
							_table[_i].push(data);
						});
						_i++;
					});
					o.output={
						"LeadAttorney":__leadAttroney,
						"Tables":_table,
						"casetype":__casetype,
						"data1":__caseNumber,
						"pacer":__pacer,
						"data2":__filed,
						"title":__caseName,
						"market":__marketSector,
						"docket_entries_table":[]
					};
				}
			});			
		});
		
		
		});
	
		</script>
			<div id="data"></div>
			<h2>Litigation</h2>
			<div id="linkedCase">
				
			</div>
			<h2>Docket</h2>
			<div id="docket"></div>
			
			
	<?php
	*/
	} 

}