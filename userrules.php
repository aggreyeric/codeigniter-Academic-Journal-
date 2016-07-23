<?php

/* created by Aggrey eric
 * @ 10:42pm 7 dec 2013
 * 
 * 
 * 
 */

class Userp extends CI_Controller{
    
   
   


           }       
             
            
        function addarticle() //create new article
{           
    $this->form_validation->set_rules('title', 'Title', 'required');

    //Text input fields
    if ($this->form_validation->run() == FALSE)
    {
         $this->load->view('header');
	 $this->load->view('submit_article');
         $this->load->view('footer');
             
    }       
    else
    {    // setting configuration settings for the upload
                $config['upload_path'] = 'submitted_articles';
	        $config['allowed_types'] = 'pdf|doc|docx';
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            //File Upload
            if (! $this->upload->do_upload())
            {
                $upload_error['upload_error'] = array('error' => $this->upload->display_errors()); 

                $this->load->view('header');
		$this->load->view('submit_article',$upload_error);
                $this->load->view('footer');
    } 

                return FALSE;
            }
           $d =  $this->upload->data('file');
            // uploading successfull, now do your further actions
            $name = $d['file_name'];
            $size = $d['file_size'];
            $date = mdate('%d - %m - %Y', time());
            
             //Add to database 
             $data = array (
               'person_id' => 1,//$this->session->userdata('person_id'),
                 'date_submitted'=> $date,
               'title' => $this->input->post('title'),
                   'filename'=> $name,
               'filesize' => $size
                 
               
             );
           
             $this->article->submit($data);

             $this->session->set_flashdata('success', 'article_added!');
             redirect('articles/submit');
   
             
             
             
             
             
             
            }       
      
    
    
    
    







?>
