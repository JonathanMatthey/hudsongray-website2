<?php

require_once VENDOR_DIR.'j7mbo/twitter-api-php/TwitterAPIExchange.php';

// Title for hashtag links
define('HASHTAG_TITLE', 'View tweets for tag: ');

// Title for user mention links
define('USER_MENTION_TITLE', 'View tweets by user: ');

class TwitterFetch {
	public function __construct() {}

	protected function linkify($text) {
	  // Linkify URLs
	  $text = preg_replace("/[[:alpha:]]+:\/\/[^<>[:space:]]+[[:alnum:]\/]/i",
	    "<a href=\"\\0\" target=\"_blank\">\\0</a>", $text);

	  // Linkify @mentions
	  $text = preg_replace("/\B@(\w+(?!\/))\b/i",
	    '<a href="https://twitter.com/\\1" title="' .
	    USER_MENTION_TITLE . '\\1">@\\1</a>', $text);

	  // Linkify #hashtags
	  $text = preg_replace("/\B(?<![=\/])#([\w]+[a-z]+([0-9]+)?)/i",
	    '<a href="https://twitter.com/search?q=%23\\1" title="' .
	    HASHTAG_TITLE . '\\1">#\\1</a>', $text);

	  return $text;
	}

	protected function fetch($url) {
		$settings = array(
		    'oauth_access_token' => "255979502-ckDsmHGrtprpgOXUfBH8bHcFuEK60SrfOt9Z2yGQ",
		    'oauth_access_token_secret' => "lLHQOKBviU8xjTco5LHbGHWsEjGOMNrk45AdEJgdmn8Ot",
		    'consumer_key' => "H1giwARon2vHhhKW7Qwv6g",
		    'consumer_secret' => "jPrAvwXwzS4gQqmtAhGAiPQ3C0KteAretZkxxgcQ"
		);

		$getfield = '?screen_name=hudsongray';
		$requestMethod = 'GET';

		$twitter = new TwitterAPIExchange($settings);
		$result = $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();

        return $result;
	}

	protected function parseFetchedData($encodedData, $number) {
		$data = json_decode($encodedData);

		$result = array();

		for ($i = 0, $l = $number; $i < $l; $i++) {
			$item = $data[$i];

			if (!is_object($item)) {
				break;
			}

			$singleResult = array();
			$singleResult['user'] = $item->user->screen_name;
			$singleResult['text'] = $item->text;
			$singleResult['linkifiedText'] = $this->linkify($item->text);
			$singleResult['date'] = $item->created_at;
			$singleResult['time'] = strtotime($item->created_at);

			array_push($result, $singleResult);
		}

		return $result;
	}

	public function getLastTweets($number) {
		$fetch = $this->fetch('https://api.twitter.com/1.1/statuses/user_timeline.json');

		return $this->parseFetchedData($fetch, $number);
	}

	public function getLastMentions($number) {
		$fetch = $this->fetch('https://api.twitter.com/1.1/statuses/mentions_timeline.json');

		return $this->parseFetchedData($fetch, $number);
	}
}

?>