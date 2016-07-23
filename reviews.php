<?php
if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Reviews extends CI_Controller{
    
            function __construct() {
        parent::__construct();
        
  
        $this->load->library('form_validation');
        $this->load->library('nativesession');
        $help = array('form','url','date');
        $this->load->helper($help);
        $this->load->model('article');
        $this->load->model('person');
        $this->load->model('review');
    }
    
    
    
    function index() {
        $this->assign_reviewer();
    }
    function assign_reviewer() {
        
 $query = "SELECT articles.article_id, persons.surname, persons.firstname, 
articles.date_submited, articles.title, articles.no_reviews FROM articles, persons WHERE 
articles.person_id  = persons.person_id AND articles.no_reviews ='yes'";
   
  $s['s'] =   $this->db->query($query);
     
  $this->load->view('header');
  $this->load->view('assigna',$s);
     
 

             
    }
    
    function ass($id){
        $data = array(
            'article_id'=>$id,
        );
        
        $data2 = array(
            'no_reviews'=>'no',
        );
        $this->review->create($data); 
        $this->article->update_article($id,$data2);
        $uri = 'reviews/';
          redirect($uri);
    }
    
    
    function accept() {
      
        
 $query =  "SELECT reviews.review_id, articles.title, persons.surname, persons.firstname, articles.filename,
 articles.article_id FROM reviews, articles, persons WHERE  reviews.article_id  = articles.article_id AND articles.person_id 
 = persons.person_id AND reviews.accepted  = 'no' ";
        
 
  $c['c'] =   $this->db->query($query);
     
  $this->load->view('header');
  $this->load->view('accept',$c);
    }
    function acc($id) {
        $p = $this->nativesession->get('person_id');
        $data2 = array(
            'person_id'=>$p,
             'accepted'=>'yes',
        );
        $this->review->update_review($id,$data2);
        $uri = 'reviews/accept';
          redirect($uri);
        
    }
    
    function setdatelines() {
        
        
    $query = "SELECT  reviews.review_id,  reviews.date_assigned,  articles.title,  persons.firstname,
persons.surname   FROM   reviews, articles,   persons   WHERE   reviews.person_id =
persons.person_id  AND  reviews.article_id = articles.article_id  AND  reviews.accepted	=
'yes'  ";
        
    
  $m['m'] =   $this->db->query($query);

  $this->load->view('header');
  $this->load->view('date',$m);
  
  }
  
     function dd() { //  handles  the submit function for setting datelines
         
      $date = $this->input->post('dd');  
      $id = $this->input->post('id');
      $data2 = array(
            'date_due'=>$date
            
        );
        $this->review->update_review($id,$data2);
        
        $uri = 'reviews/setdatelines';
          redirect($uri);
    }
        
    
    
    function submitr() {//submits review
        
     $config['upload_path'] = 'reviewed_articles';
	        $config['allowed_types'] = 'pdf|doc|docx';
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['encrypt_name'] = TRUE;
                 $config['max_size'] = '5000';
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')) {
            $e['error'] = $this->upload->display_errors();
            $this->load->view('header');
	    $this->load->view('submit_review',$e);
            
    
        } else {
	   	  //here $file_data receives an array that has all the info
		  //pertaining to the upload, including 'file_name'
            $file_data = $this->upload->data();
              $id =  $this->input->post('id');
             $data = array (
               'by' => $this->input->post('by'),
                 'title' => $this->input->post('abstract'),
                 'issue_id' => $this->input->post('issue_id'),
               'title' => $this->input->post('title'),
                    'completed' => 'yes',
                   'file'=> $file_data['file_name']
                 
              
            );
            
         $this->db->where('review_id',$id);
         $this->db->update('reviews',$data);
            //print_r($file_data); 
            $this->session->set_flashdata('success', 'article_added!');
            $this->load->view('header');
	    $this->load->view('submit_review');
          
        }
    }
    
    
    
   //pages on the front page 
    
   function current() {
       
       $this->load->view('sliderheader');
        $this->load->view('current');
        
       
       
       
       
   }
    
     function arc1() {
       
       $this->load->view('sliderheader');
        $this->load->view('arc1');
         
       
       
       
       
   }
    
   function aboutus() {
      $this->load->view('sliderheader');
        $this->load->view('aboutus'); 
   }
   
   
   function contactus() {
       $this->load->view('sliderheader');
        $this->load->view('contactus');  
   }
   
    
  
    }
    
    
     
    




