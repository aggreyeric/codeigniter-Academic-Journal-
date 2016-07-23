<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* created by Aggrey eric
 * @ 10:42pm 7 dec 2014
 */
class Articles extends CI_Controller
    {
    
    public  $upload;
            function __construct() {
        parent::__construct();
        $this->load->model('article');
        $helper=array('form','url','date');
        $this->load->helper($helper);
        $this->load->library('form_validation');
         $this->load->library('nativesession');
        
    }
    function submitted() {
        $id = $this->nativesession->get('person_id');
        $data['papers'] = $this->article->article_submitted($id) ;
        $this->load->view('header');
	$this->load->view('submitted',$data);
       
    }
    
    
    function do_upload($field) {
        $config['upload_path'] = './submitted_articles/';
	$config['allowed_types'] = 'pdf|doc|docx';
        $config['overwrite'] = FALSE;
        $config['remove_spaces'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field)) {
            $data = array('error' => $this->upload->display_errors());
            // uploading failed. $error will holds the errors.
        } else {
            $data = array('upload_data' => $this->upload->data($field));
            
        }

        return $data;
    }
    
    
     
            
            
            
            
            function addarticle() {
                $config['upload_path'] = 'submitted_articles';
	        $config['allowed_types'] = 'pdf|doc|docx';
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['encrypt_name'] = TRUE;
                 $config['max_size'] = '5000';
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('file')) {
            $e['error'] = $this->upload->display_errors();
            $this->load->view('header');
	    $this->load->view('submit_article',$e);
            
    
        } else {
	   	  //here $file_data receives an array that has all the info
		  //pertaining to the upload, including 'file_name'
            $file_data = $this->upload->data();
          $now = date('Y-m-d H:i:s');
             
             $data = array (
               'person_id' => 1,//$this->session->userdata('person_id'),
                 'date_submited'=> $now,
               'title' => $this->input->post('title'),
                   'filename'=> $file_data['file_name'],
               'filesize' => $file_data['file_size']
            );
            
            $this->article->submit($data);
            //print_r($file_data); 
            $this->session->set_flashdata('success', 'article_added!');
            $this->load->view('header');
	    $this->load->view('submit_article');
          
        }
    }
    
    
    function delete($id) {
        $this->article->remove_article($id);
        
        $uri = 'articles/submitted';
          redirect($uri);
        
    }
    
    
    
}
   
    
?>
