<?php
class Account extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('enseignantModel');
        $this->load->library('aauth');
        $this->load->library('form_validation');
    }
    public function verification(int $idAauth, string $keyVerif)
    {
        
    }
    public function create()
    {
        LoadValidationRules($this->enseignantModel, $this->form_validation);
        $this->form_validation->set_rules('password','Password','required|max_length[100]');
        $this->form_validation->set_rules('passwordConfirmation','Confirmez le mot de passe ','required|max_length[100]|callback_password_check');
        if ($this->form_validation->run())
        {
            $idAauth=$this->aauth->create_user($email,$password);
            $params=array(
                    'nom'=>$this->input->post('nom'),
                    'prenom'=>$this->input->post('prenom'),
                    'email'=>$email,
                    'idAuth'=>$idAauth
                );
            $enseignant_id=$this->enseignantModel->add_enseignant($params);
            // on l'affecte au groupe Enseignant
            $this->aauth->add_member($idAauth,'Enseignant');
            $this->attente_confirmation($email);
        }
        else
        {
            $data['title']='Inscription au rallye lecture';
            $this->load->view('AppHeader',$data);
            $this->load->view('AccountCreate');
            $this->load->view('AppFooter');
        }
        
    }
    public function password_check()
    {
        $password=$this->input->post('password');
        $passwordConfirmation=$this->input->post('passwordConfirmation');
        if($password!=$passwordConfirmation)
        {
            $this->form_validation->set_message('password_check','le mot de passe de confirmation est différent du mot de passe initial');
            return false;
        }
        else
        {
            return true;
        }
    }
    public function recaptcha_check($resp)
    {
        
    }
    public function edit()
    {
        
    }
    public function attente_confirmation()
    {
        $data['title']="Confirmation de votre inscription";
        $date['email']=$email;
        $this->load->view('AppHeader',$data);
        $this->load->view('AccountConfirmation',$data);
        $this->load->view('AppFooter');
    }
    
}