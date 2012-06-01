<?php defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Show Facebook feed in your site
 * 
 * @author	Joel Vardy <info@joelvardy.com>
 */
class Widget_Facebook extends Widgets
{


	/* Widget title */
	public $title = array(
		'en' => 'Facebook Feed'
	);

	/* Widget description */
	public $description = array(
		'en' => 'Display Facebook feed on your website.'
	);

	/* Widget author */
	public $author = 'Joel Vardy';

	/* Widget authors website */
	public $website = 'http://joelvardy.com/';

	/* Widget version */
	public $version = '1.1';

	/* Widget fields */
	public $fields = array(
		array(
			'field' => 'app_id',
			'label' => 'Facebook App ID',
			'rules' => 'numeric'
		),
		array(
			'field' => 'app_secret',
			'label' => 'Facebook App Secret Key',
			'rules' => 'required'
		),
		array(
			'field' => 'username',
			'label' => 'User/Page Username',
			'rules' => 'required'
		),
		array(
			'field' => 'number',
			'label' => 'Number of posts',
			'rules' => 'numeric'
		)
	);

	/* Authorisation URL */
	private $authorisation_url = 'https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=[APP_ID]&client_secret=[APP_SECRET]';

	/* Feed URL */
	private $feed_url = 'https://graph.facebook.com/[USERNAME]/feed/';

	/* Duration to cache feed for */
	private $cache_duration = 600;


	/**
	 * Return contents of URL
	 *
	 * @param	str [$url] The URL you wish to return the contents of
	 * @return	str The contents of the passed URL
	 */
	public function _read_url($url)
	{

		/* Define options */
		$options = array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 20
		);

		/* Initialise cURL */
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$url_contents = curl_exec($ch);
		curl_close($ch);

		/* Return URL contents */
		return $url_contents;

	}


	/**
	 * Main method
	 *
	 * This is the method which is run each time the widget needs to be parsed.
	 *
	 * @param	array [$options] The options which were set when the user setup the widget
	 * @return	array The username and posts from that user
	 */


	/* Main method */
	public function run($options)
	{

		/* Fetch posts from cache */
		if ( ! $posts = $this->pyrocache->get('facebook-'.$options['username']))
		{

			/* Retrieve authorisation token */
			$authorisation_url = str_replace(array('[APP_ID]', '[APP_SECRET]'), array($options['app_id'], $options['app_secret']), $this->authorisation_url);
			$authorisation_token = $this->_read_url($authorisation_url);

			/* Retrieve posts */
			$feed_url = str_replace('[USERNAME]', $options['username'], $this->feed_url).'?'.$authorisation_token;
			$feed = $this->_read_url($feed_url);

			/* Decode feed */
			$feed = json_decode($feed, true);

			/* Check for error */
			if (isset($feed['error']))
			{
				return false;
			}

			/* Iterate through feed */
			foreach ($feed['data'] as $post)
			{

				/* Only use is a message is set */
				if (isset($post['message']))
				{
					/* Add post to array */
					$posts[] = array(
						'id' => $post['id'],
						'message' => $post['message'],
						'posted' => strtotime($post['created_time']),
						'likes' => (isset($post['likes']['count']) ? $post['likes']['count'] : 0),
						'comments' => (isset($post['likes']['count']) ? $post['comments']['count'] : 0)
					);
				}

			}

			/* Write posts to cache */
			$this->pyrocache->write($posts, 'facebook-'.$options['username'], $this->cache_duration);

		}

		/* Iterate through posts */
		foreach ($posts as $i => $post)
		{

			/* Add post to return array */
			$return[] = $post;

			/* Once we have 'saved' enough posts break - this may seem a little unclean - but by doing this we can use the same cached feed and multiple instances of the widget can call with different numbers of posts to display */
			if (($i + 1) == $options['number'])
			{
				break;
			}

		}

		/* Return posts */
		return array(
			'username' => $options['username'],
			'posts' => $return ? $return : array(),
		);

	}


}


/* End of file facebook.php */