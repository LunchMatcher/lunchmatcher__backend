<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Availability extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->_setAsAdmin();				
		$this->load->model('availability_model');
		$this->user 	= $this->session->userdata('user');
		if($this->user=='')
			redirect('admin');
	}	
	function index(){
		redirect('admin/availability/lists');
	}
	
	public function meetings(){	
	

			$data['startDate']	=	(!$this->input->post('startDate') ? ($this->input->get('startDate') ? $this->input->get('startDate') : date('Y-m-d')):$this->input->post('startDate'));
		$data['endDate']	=	(!$this->input->post('endDate') ? ($this->input->get('endDate') ? $this->input->get('endDate') : date('Y-m-d')):$this->input->post('endDate'));

			$data['limit']	=	(!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') :$page_limit):$this->input->post('limit'));
			
			//$data['availablelist']=$this->availability_model->get_all_availability();
			$data['meetingsBydate']=$this->availability_model->getMeetingsBydate($data['startDate'],$data['endDate']);
			
		//echo "<pre>"; print_r($data['meetingsBydate']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/availability/meetings', $data, true);
		$this->_render_output($output);
	}	
	public function lists(){	
	

			$data['startDate']	=	(!$this->input->post('startDate') ? ($this->input->get('startDate') ? $this->input->get('startDate') : date('Y-m-d')):$this->input->post('startDate'));
		$data['endDate']	=	(!$this->input->post('endDate') ? ($this->input->get('endDate') ? $this->input->get('endDate') : date('Y-m-d')):$this->input->post('endDate'));

			$data['limit']	=	(!$this->input->post('limit') ? ($this->input->get('limit') ? $this->input->get('limit') :$page_limit):$this->input->post('limit'));
			
			//$data['availablelist']=$this->availability_model->get_all_availability();
			$data['availablelistBydate']=$this->availability_model->getAvailabilityByDate($data['startDate'],$data['endDate']);
			
			$data['todayMatches'] = $this->availability_model->getMatchHistoryByMatchlog($data['startDate'],$data['endDate']);
			
			//echo "<pre>"; print_r($data['availablelistBydate']); echo "</pre>"; exit;
			
			$config['total_rows'] = count($data['availablelistBydate']);
			$config['per_page'] =  getConfigValue('default_pagination');
			$this->db->limit($config['per_page'],$_REQUEST['per_page']);
			$params = '?t=1';
		
		//	$this->load->library('pagination');
		//	$config['base_url'] = site_url("admin/availability/lists")."/".$params;
			
		// load pagination class
		
/*			$config['page_query_string'] = TRUE;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
			$config['full_tag_open'] = "<ul class='pagination'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";			
			$data['page'] = $_REQUEST['per_page'];
		$this->pagination->initialize($config);	
*/
	
		//echo $this->availability_model->db->last_query();exit;
		//echo "<pre>"; print_r($data['userlist']); echo "</pre>"; exit;
		$output['output']=$this->load->view('admin/availability/lists', $data, true);
		$this->_render_output($output);
	}

	public function check_cron()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$cron_check = $_POST['cron_check'];
			if($cron_check != '' ){
				$result = json_decode($cron_check);
				//echo "<pre>"; print_r($result); echo "</pre>"; 
				$data['result'] = $result;
			}
		}
		$output['output']=$this->load->view('admin/availability/check_cron', $data, true);
		$this->_render_output($output);
	}
	public function check_place()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$latitude = $_REQUEST['latitude'];
			$longitude = $_REQUEST['longitude'];
			$keyword = $_REQUEST['keyword'];
			$type = $_REQUEST['type'];
			$radius = $_REQUEST['radius'];
			$distance = $_REQUEST['distance'];
			
			
			$latlon 	= 	$latitude.','.$longitude;
			$radius 	= 	$radius * 1000; 
			$api_key = $this->config->item('google_place_api_key');
		
			/*$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword='.$keyword.'&location='.$latlon.'&radius='.
										$radius.'&types='.$type.'&rankBy='.$distance.'&key='.$api_key;*/
										
			$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$latlon.
								'&sensor=true&type='.$type.'&radius='.$radius.'&rankby=distance&key='.$api_key;
										
			if($nextpage != '')
				$url = $url.'&pagetoken='.$nextpage;
			
			$venues 	 =	file_get_contents($url);
			$venues 	 = json_decode($venues,true);	
			
			$data['url'] = $url;	
		    $data['result'] = $venues;
			
		}
		$output['output']=$this->load->view('admin/availability/check_place', $data, true);
		$this->_render_output($output);
	}
	public function details()
	{
		$member_id=$_POST['member_id'];
		$match_logid=$_POST['match_logid'];
		$data['details']=$this->availability_model->getAvailabilityDetail($member_id,$match_logid);
		$data['lattitude']=$data['details']['match_latitude'];
		$data['longitude']=$data['details']['match_longitude'];
		$data['locations']		=	"['', ".$data['lattitude'].", ".$data['longitude'].", '1']";	
		$data['match_radius']	=	$data['details']['match_radius']*1000;
		$data['all_location_details']	=	$this->availability_model->getAvailabilRestaurant($match_logid,$member_id);
		//echo "<pre>"; print_r($data['all_location_details']); echo "</pre>"; exit;
		$data['googleKey']='AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q';
		$match_date=date("Y-m-d", strtotime ($data['details']['match_time_from']));
		$data['getmatchlocation']=$this->availability_model->getMatchLocations($match_date,$member_id);
		/*if(count($data['AvailableRestaurants'])!=0)
		{
			foreach($data['AvailableRestaurants'] as $val){
				$google_api_url ="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$val['venue_id']."&key=AIzaSyCSB01UwvVbd63eV_scq-rOD4AEirD8z9Q";
				
				$location_result	=	file_get_contents($google_api_url);
				$array = json_decode($location_result,TRUE);
				//echo '<pre>';print_r($array);
				if($array['status']!='ZERO_RESULTS'){
					if(!isset($array['result'][0])){
						$res	=$array['result'];
						$array	=array();
						$array[0]	=	$res;
					}else{
						$array = $array['result'];
					}
					for($i=0;$i<count($array);$i++)
					{
						$location_details[$i]['restaurant_name'] = $array[$i]['name'];
						$location_details[$i]['latitude'] = sprintf("%.5f",round($array[$i]['geometry']['location']['lat'],5));
						$location_details[$i]['longitude'] = sprintf("%.5f",round($array[$i]['geometry']['location']['lng'],5));
						$location_details[$i]['venue_id'] = $array[$i]['place_id'];
						$location_details[$i]['venue_type'] = ucfirst($array[$i]['type'][0]);
						$location_details[$i]['rating'] = $array[$i]['rating'];
						$location_details[$i]['favourite'] = $val['favourite'];
						$location_details[$i]['phone_number'] = $array[$i]['international_phone_number'];
						$location_details[$i]['address'] =$array[$i]['vicinity'];
						$location_details[$i]['photokey'] =$array[$i]['photos'][0]['photo_reference'];
						
					 }
					 
					 
					 $all_location_details[]=$location_details;
					//echo "<pre>"; print_r($all_location_details); echo "</pre>";
				}
			}
		
		}
		$data['all_location_details']=$all_location_details;
		*/
			//$data['details2']=$this->availability_model->locations($lattitude,$longitude);	
		echo $this->load->view('admin/availability/viewDetails',$data);	
	}
	
	public function getAvailability()
	{
	$match_logid = $_POST['match_logid'];
	$availability=$this->availability_model->getAvailability($match_logid);
	$new_array = array();
	$arraynew='';
	$i=0;
	
	foreach($availability as $array)
    {
	
    
        //array_push($new_array, $val);
		
		$i=1;
		foreach($array as $val){
		$arraynew.=	 $val;		
			if(count($data)!=$i)
		$arraynew.=	 ',';
			$i++;
		}
		
        
    }
	
	echo $arraynew;
		exit;
	}

}