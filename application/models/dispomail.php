<?php
namespace application\Models;

class dispomail Extends \core\Model
{
	private $cpAccount;
	private $cpPassword;
	private $emailDomain;

	private $server_ip_or_domain;

	public function __construct() 
	{
		parent::__construct();

		include $_SERVER['DOCUMENT_ROOT'].BASE_PATH.'application/config/mail.php';

		$this->server_ip_or_domain = $main_config['server_ip_or_domain'];
		$this->cpAccount    = $main_config['cp_user'];
		$this->cpPassword   = $main_config['cp_password'];
		$this->emailDomain  = $main_config['domain'];
		$this->mailServerSubDomain = $main_config['mail_subddomain'];
	}

	public function generateEmail()
	{
		$this->load->library('xmlapi', ($this->server_ip_or_domain));

		$this->xmlapi->password_auth($this->cpAccount, $this->cpPassword);
		$this->xmlapi->set_port(2082);
		$this->xmlapi->set_output("json");
		$this->xmlapi->set_debug(0);

		$mailAcc  = $this->genRandomString();
		$mailPass = $this->genRandomString(16);

		$this->session->setSession('mailAcc', $mailAcc);
		$this->session->setSession('mailPass', $mailPass);

		$this->xmlapi->api1_query($this->cpAccount, "Email", "addpop", array($mailAcc, $mailPass, 10, $this->emailDomain) );
	}

	public function removeEmail($mail, $password)
	{
		$this->load->library('xmlapi', ($this->server_ip_or_domain));

		$this->xmlapi->password_auth($this->cpAccount, $this->cpPassword);
		$this->xmlapi->set_port(2082);
		$this->xmlapi->set_output("json");
		$this->xmlapi->set_debug(0);

		$mailAcc  = $mail;
		$mailPass = $password;

		$this->xmlapi->api1_query($this->cpAccount, "Email", "delpop", array($mailAcc, $mailPass, $this->emailDomain) );
	}

	public function getEmailsOverviewAndBody($email, $password)
	{
		$mails_info = array();

		$srv  = '{'.$this->mailServerSubDomain.'.'.$this->emailDomain.':110/pop3/novalidate-cert}';
		$mbox = @imap_open($srv.'INBOX', $email."@".$this->emailDomain,$password);
		if(!$mbox) die('<center>Our server is busy. Please try again in a few seconds.</center>');
		$MC   = imap_check($mbox);

		$result = imap_fetch_overview($mbox,"1:{$MC->Nmsgs}",0);

		$i = 0;
		foreach ($result as $overview) 
		{
			$body = imap_fetchbody($mbox, $overview->msgno, 0);
			$msg = $this->retrieve_message($mbox, $overview->msgno);
			$body = utf8_encode(quoted_printable_decode($msg['body']));
			$header = imap_headerinfo($mbox, $overview->msgno);
			$from_addr = $header->from[0]->mailbox . "@" . $header->from[0]->host;

			$mails_info[$i]['msg_id'] = $overview->msgno;
			$mails_info[$i]['date'] = $overview->date;
			$mails_info[$i]['from'] = $overview->from;
			$mails_info[$i]['from_addr'] = $from_addr;
			$mails_info[$i]['subject'] = $overview->subject;
			$mails_info[$i]['body'] = $body;

			$i++;
		}

    	imap_close($mbox);

    	return array_reverse($mails_info);
	}

	private function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false)
	{
	    if (!$structure) {
	        $structure = imap_fetchstructure($imap, $uid, FT_UID);
	    }
	    if ($structure) {
	        if ($mimetype == $this->get_mime_type($structure))
	        {
	            if (!$partNumber) 
	            {
	                $partNumber = 1;
	            }
	            $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
	            switch ($structure->encoding) {
	                case 3:
	                    return imap_base64($text);
	                case 4:
	                    return imap_qprint($text);
	                default:
	                    return $text;
	            }
	        }

	        if ($structure->type == 1) 
	        {
	            foreach ($structure->parts as $index => $subStruct)
	            {
	                $prefix = "";
	                if ($partNumber) 
	                {
	                    $prefix = $partNumber . ".";
	                }
	                $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
	                if ($data) 
	                {
	                    return $data;
	                }
	            }
	        }
	    }
	    return false;
	}

	private function get_mime_type($structure)
	{
	    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

	    if ($structure->subtype) 
	    {
	        return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
	    }
	    return "TEXT/PLAIN";
	}

	private function getBody($uid, $imap)
	{
	    $body = $this->get_part($imap, $uid, "TEXT/HTML");

	    if ($body == "") 
	    {
	    	//$this->load->library('BBCoder');
	        $body = preg_replace('/(http[s]{0,1}\:\/\/\S{4,})\s{0,}/ims', '<a href="$1" target="_blank">$1</a> ', ($this->get_part($imap, $uid, "TEXT/PLAIN")));
	        $body = nl2br($body);
	    }
	    return $body;
	}

	private function retrieve_message($mbox, $messageid)
	{
		$message = array();
		$header = imap_header($mbox, $messageid);
		$structure = imap_fetchstructure($mbox, $messageid);
		$message['subject']     = @$header->subject;
		$message['fromaddress'] = @$header->fromaddress;
		$message['fromemail']   = @$header->from[0]->mailbox . '@' . $header->from[0]->host;
		$message['toaddress']   = @$header->toaddress;
		$message['ccaddress']   = @$header->ccaddress;
		$message['date'] = @$header->date;
		if ($structure->subtype == "ALTERNATIVE") $message['body'] = imap_fetchbody($mbox, $messageid, 2);
		else $message['body'] = imap_fetchbody($mbox, $messageid, 1.2);
		$message['body'] = $this->getBody($messageid, $mbox);
		$messageB = $message;
		$overview = imap_fetch_overview($mbox, $messageid, 0);
		$message = imap_fetchbody($mbox, $messageid, 2);
		$structure = imap_fetchstructure($mbox, $messageid);
		$attachments = array();
		if (isset($structure->parts) && count($structure->parts))
		{
			for ($i = 0; $i < count($structure->parts); $i++)
			{
				$attachments[$i] = array(
					'is_attachment' => false,
					'filename' => '',
					'name' => '',
					'attachment' => ''
				);
				if ($structure->parts[$i]->ifdparameters)
				{
					foreach($structure->parts[$i]->dparameters as $object)
					{
						if (strtolower($object->attribute) == 'filename')
						{
							$attachments[$i]['is_attachment'] = true;
							$attachments[$i]['filename'] = $object->value;
						}
					}
				}

				if ($structure->parts[$i]->ifparameters)
				{
					foreach($structure->parts[$i]->parameters as $object)
					{
						if (strtolower($object->attribute) == 'name')
						{
							$attachments[$i]['is_attachment'] = true;
							$attachments[$i]['name'] = $object->value;
						}
					}
				}

				if ($attachments[$i]['is_attachment'])
				{
					$attachments[$i]['attachment'] = imap_fetchbody($mbox, $messageid, $i + 1);
					if ($structure->parts[$i]->encoding == 3)
					{
						$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
					}
					elseif ($structure->parts[$i]->encoding == 4)
					{
						$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
					}
				}
			}
		}

	    if(count($attachments)!=0){


	        foreach($attachments as $at){

	            if($at['is_attachment']==1){
	                    $filename = 'files/'.$at['filename']; $file_extension = @end(explode(".", $filename));
	                    if($file_extension == 'jpg' || $file_extension == 'png') {
	                    file_put_contents($filename, $at['attachment']);
	                    $messageB['body'] = preg_replace("#src=\"(.+)\"#imsU", "src='".$filename."'", utf8_encode(quoted_printable_decode($messageB['body'])));
	                    }
	                }
	            }

	        }


	  return $messageB;
	}

	public function genRandomString($length = 10)
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) 
	    {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    return $randomString;
	}

}