<?php
namespace Web10\SAdmin;

class WebUtil
{
	function __construct()
	{
	}
	
	public function redirect($url, $msg=null, $isError=false)
	{
		if (!empty($msg))
		{
			if ($isError)
				$_SESSION['error'] = $msg;
			else 
				$_SESSION['message'] = $msg;
		}
		header("Location: $url");
	}
}
?>
