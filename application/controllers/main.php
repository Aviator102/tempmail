<?php
namespace application\Controllers;

class Main Extends \core\Controller
{
	private $dispo_model;

	public function __construct()
	{
		parent::__construct();
		$this->dispo_model = $this->load->model('dispomail');
	}

	public function site_index() 
	{
		$mail_acc = $this->session->getSession('mailAcc');

		if(isset($mail_acc) || strlen($mail_acc) >= 10) $this->remove();

		$data['title'] = 'DispoMail - Temporary, Disposable Email Service.';
        $this->load->view('index', $data);
	}

	public function about()
	{
		$data['title'] = 'DispoMail - About Us.';
        $this->load->view('about_us', $data);
	}

	public function generate()
	{
		$this->dispo_model->generateEmail();
		header("Location: ".BASE_PATH."main/dispomail/");
	}

	public function dispomail()
	{
		$mail_acc = $this->session->getSession('mailAcc');
		$mail_pwd = $this->session->getSession('mailPass');

		if(!isset($mail_acc) || strlen($mail_acc) < 10 && !isset($_GET['ajax'])) 
		{
			$this->dispo_model->generateEmail();
			$mail_acc = $this->session->getSession('mailAcc');
			$mail_pwd = $this->session->getSession('mailPass');
		}


		include $_SERVER['DOCUMENT_ROOT'].BASE_PATH.'application/config/mail.php';

		$data['email_account'] = $mail_acc.'@'.$main_config['domain'];

		$data['emails'] = $this->dispo_model->getEmailsOverviewAndBody($mail_acc, $mail_pwd);


		$data['title'] = 'DispoMail - '. $data['email_account'];

		$this->load->view('dispomail', $data);
	}

	public function remove()
	{
		$mail_acc = $this->session->getSession('mailAcc');
		$mail_pwd = $this->session->getSession('mailPass');
		$this->session->setSession('mailAcc', '');
		$this->session->setSession('mailPass', '');
		$this->dispo_model->removeEmail($mail_acc, $mail_pwd);
	}
}