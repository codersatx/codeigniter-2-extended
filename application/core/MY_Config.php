<?php

class MY_Config extends CI_Config {
	
	function site_url($uri = '')
	{
		if ($uri == '')
		{
			if ($this->item('base_url') == '')
			{
				return '';
			}
			else
			{
				return $this->slash_item('base_url');
			}
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}

			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $this->slash_item('base_url').trim($uri, '/').$suffix;
		}
		else
		{
			if (is_array($uri))
			{
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val)
				{
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix.$key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			if ($this->item('base_url') == '')
			{
				return '?'.$uri;
			}
			else
			{
				return $this->slash_item('base_url').'?'.$uri;
			}
		}
	}
	
}

?>