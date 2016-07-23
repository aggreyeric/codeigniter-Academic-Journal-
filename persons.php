<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* created by Aggrey eric
 * CI contoller for handling login details and user permissions for
 * authors,editors reviewers and readers.
 * 20th november 2014
 * 
 */
class Persons extends CI_Controller
    {  
    function __construct() 
    {
        parent::__construct();
        $this->load->model('person');
         $this->load->model('article');
        $this->load->library('form_validation');
        $this->load->library('nativesession');
         $this->load->library('session');
        $help = array('form','url','date');
        $this->load->helper($help);
        
    }
            
      function index() {
       $this->load->view('sliderheader');
        $this->load->view('home');
       
        
    }
    
      function login() {
        
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $p =  $this->person->login($email,$password);
        
        if(!$p)
        {
            $this->load->view('sliderheader');
            $this->load->view('login');
            $this->load->view('footer');
            
        }
 else {
             $this->nativesession->set('person_id',$p['person_id']);
             $this->nativesession->set('pay',$p['subscribtion_id']); 
             $this->nativesession->set('surname',$p['surname']);
             $this->nativesession->set('title',$p['title']);
             $this->nativesession->set('status',$p['status']);
             $this->urpage();
 } 
        }
    
    
    function logout() {
        session_destroy();
       $uri = 'persons/index';
       redirect($uri);
        
    }
    function register() {
        $datestring = "Year: %Y Month: %m Day: %d - %h:%i %a";
        $time = time();

       $date = mdate($datestring, $time);
       
       $config = array(
               array(
                     'field'   => 'title', 
                     'label'   => 'Title', 
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'first', 
                     'label'   => 'First Name', 
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'surname', 
                     'label'   => 'Surname', 
                     'rules'   => 'required'
                  ),   
               array(
                     'field'   => 'email', 
                     'label'   => 'Email', 
                     'rules'   => 'required|is_unique[persons.email]|valid_email'
                  ),
           
           array(
                     'field'   => 'post', 
                     'label'   => 'Postal Address', 
                     'rules'   => 'required'
                  ),
            array(
                     'field'   => 'institution', 
                     'label'   => 'Institution', 
                     'rules'   => 'required'
                  ),
           
            array(
                     'field'   => 'status', 
                     'label'   => 'User Type', 
                     'rules'   => 'required'
                  ),
            array(
                     'field'   => 'password', 
                     'label'   => 'Password', 
                     'rules'   => 'required|min_length[6]'
                  ),
            );

$this->form_validation->set_rules($config);
if ($this->form_validation->run() == true)
		{	
       
    $data = array(
                
        'title'=>$_POST['title'],
        'firstname'=>$_POST['first'],
        'surname'=>$_POST['surname'],
        'email'=>$_POST['email'],
        'postal_address'=>$_POST['post'],
        'institution'=>$_POST['institution'],
            'status'=>$_POST['status'],
        'password'=>$_POST['password']
        
        
    );
   $this->person->register_person($data);
   $this->session->set_flashdata('message', "<p> Registration successfull.</p>");
			
    redirect(base_url().'persons');
		}else{

$this->data['message'] = (validation_errors() ? validation_errors() : 
$this->session->flashdata('message'));


		        $this->data['first'] = array(
				'name'  	=> 'first',
				'id'    	=> 'first',
				'type'  	=> 'text',
		
				'value' 	=> $this->form_validation->set_value('first')
			);			
			$this->data['surname'] = array(
				'name'  	=> 'surname',
				'id'    	=> 'surname',
				'type'  	=> 'text',
                        
				'value' 	=> $this->form_validation->set_value('surname'),
			);
			$this->data['email'] = array(
				'name'  	=> 'email',
				'id'    	=> 'email',
				'type'  	=> 'text',
				'value' 	=> $this->form_validation->set_value('email'),
			);
			$this->data['post'] = array(
				'name'  => 'post',
				'id'    => 'post',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('post'),
			);
                        $this->data['institution'] = array(
				'name'  => 'institution',
				'id'    => 'post',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('institution'),
			);
                        $this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'post',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			 $this->load->view('sliderheader');
			$this->load->view('vregister', $this->data);
                        $this->load->view('footer');
                        
		}
	}
   
        function update() {
            $id = $this->nativesession->get('person_id');
            
            $config = array(
               array(
                     'field'   => 'title', 
                     'label'   => 'Title', 
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'first', 
                     'label'   => 'First Name', 
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'surname', 
                     'label'   => 'Surname', 
                     'rules'   => 'required'
                  ),   
               array(
                     'field'   => 'email', 
                     'label'   => 'Email', 
                     'rules'   => 'required'
                  ),
           
           array(
                     'field'   => 'post', 
                     'label'   => 'Postal Address', 
                     'rules'   => 'required'
                  ),
            array(
                     'field'   => 'institution', 
                     'label'   => 'Institution', 
                     'rules'   => 'required'
                  ),
           
           
            array(
                     'field'   => 'password', 
                     'label'   => 'Password', 
                     'rules'   => 'required|min_length[6]'
                  ),
            );

$this->form_validation->set_rules($config);
if ($this->form_validation->run() == true)
		{	
       
    $data = array(
                
        'title'=>$_POST['title'],
        'firstname'=>$_POST['first'],
        'surname'=>$_POST['surname'],
        'email'=>$_POST['email'],
        'postal_address'=>$_POST['post'],
        'institution'=>$_POST['institution'],
            'status'=>$_POST['status'],
        'password'=>$_POST['password']   
    );
   $this->person->update_person($id,$data);
   $this->session->set_flashdata('message', "<p style= 'color:red;'>Record successfully updated.</p>");
			
    redirect(base_url().'index.php/persons/update');
		}else{

$this->data['message'] = (validation_errors() ? validation_errors() : 
$this->session->flashdata('message'));
$person = $this->person->get_person($id);
   $this->data['title'] = array(
				'name'  	=> 'title',
				'id'    	=> 'title',
				'type'  	=> 'text',
	
				'value' 	=> $person['title'],
                                );

		        $this->data['first'] = array(
				'name'  	=> 'first',
				'id'    	=> 'first',
				'type'  	=> 'text',
				
				'value' 	=> $person['firstname']
			);			
			$this->data['surname'] = array(
				'name'  	=> 'surname',
				'id'    	=> 'surname',
				'type'  	=> 'text',
                           
				'value' 	=> $person['surname'],
			);
			$this->data['email'] = array(
				'name'  	=> 'email',
				'id'    	=> 'email',
				'type'  	=> 'text',
                'value' 	=> $person['email'],
			);
			$this->data['post'] = array(
				'name'  => 'post',
				'id'    => 'post',
				'type'  => 'text',
				'value' => $person['postal_address'],
			);
                        $this->data['institution'] = array(
				'name'  => 'institution',
				'id'    => 'post',
				'type'  => 'text',
				'value' => $person['institution'],
			);
                        $this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'post',
				'type'  => 'password',
				'value' => $person['password'],
			);
			 $this->load->view('header');
			$this->load->view('vupdate', $this->data);
                    
		}
            
        }
   
        
function checkperms() {
    
    if($this->nativesession->get('pay')== 2 ){
        
      //$msg = "your subscription is active ";
       
        
    }  else {
        
           redirect('persons/login');
    }

    
}




function urpage() {
    
    $h = $this->nativesession->get('status');
    $p = $this->nativesession->get('pay');
    if($h && $p == 2 ){
     switch ($h) {
                case 'editor':
                  $this->load->view('header');
                  $this->load->view('navcontrol');
                    $this->load->view('editor');
                     
                    break;
                 
                case 'reviewer':
                    $this->load->view('header');
                    $this->load->view('navcontrol');
                    $this->load->view('reviewer');
                  
                    
                    break;
                case 'author':
                    
                    $this->load->view('header');
                    $this->load->view('navcontrol');
                     $this->load->view('author');
                     
                    break;
                  case 'reader': 
                      $uri = 'persons/index';
                     redirect($uri);
        
                    break;

                default:
                    
                     $this->load->view('header');
                     $this->load->view('home');
                     $this->load->view('footer');
                    break;
            
 
}
    }  else {
          $uri = 'persons/index';
          redirect($uri);
    }
}




function read($id) {
    
$query = "SELECT reviews.file  FROM reviews WHERE  reviews.article_id = '$id'";
   
  $s['s'] =  $this->db->query($query);   
     $this->load->view('reada', $s);                       
                
  }
  
  function allpersons () {
       $query =  "SELECT * FROM persons ";
        
         $p['p'] =   $this->db->query($query);
            $this->load->view('header');
           $this->load->view('navcontrol');
           $this->load->view('allpersons',$p);
      
  }
  function delete($id) {
      $this->person->delete_person($id);
      redirect('persons/allpersons');
  }
       
    }
    
    





    

?>