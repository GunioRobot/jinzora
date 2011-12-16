<?php
	define('JZ_SECURE_ACCESS','true');
	/**
	* - JINZORA | Web-based Media Streamer -
	*
	* Jinzora is a Web-based media streamer, primarily desgined to stream MP3s
	* (but can be used for any media file that can stream from HTTP).
	* Jinzora can be integrated into a CMS site, run as a standalone application,
	* or integrated into any PHP website.  It is released under the GNU GPL.
	*
	* - Resources -
	* - Jinzora Author: Ross Carlson <ross@jasbone.com>
	* - Web: http://www.jinzora.org
	* - Documentation: http://www.jinzora.org/docs
	* - Support: http://www.jinzora.org/forum
	* - Downloads: http://www.jinzora.org/downloads
	* - License: GNU GPL <http://www.gnu.org/copyleft/gpl.html>
	*
	* - Contributors -
	* Please see http://www.jinzora.org/team.html
	*
	* - Code Purpose -
	* - This page handles all API requests for add-on services
	*
	* @since 04.14.05
	* @author Ross Carlson <ross@jinzora.org>
	* @author Ben Dodson <ben@jinzora.org>
	*/
	// Let's set the error reporting level
	//@error_reporting(E_ERROR);

	// Let's include ALL the functions we'll need
	// Now we'll need to figure out the path stuff for our includes
	// This is critical for CMS modes
	$include_path = ""; $link_root = ""; $cms_type = ""; $cms_mode = "false";
    $backend = ""; $jz_lang_file = ""; $skin = ""; $my_frontend = "";

	include_once('system.php');
	include_once('settings.php');
	include_once('backend/backend.php');
	include_once('playlists/playlists.php');
	include_once('lib/general.lib.php');
	include_once('lib/jzcomp.lib.php');
	include_once('services/class.php');
	include_once('frontend/display.php');

	$this_page = setThisPage();


	// Let's create our user object for later
	$jzUSER = new jzUser();

	if (isset($_GET['user']) && isset($_GET['pass'])) {
		$store_cookie = true;
		// Are they ok?
		if ($jzUSER->login($_GET['user'],$_GET['pass'],$store_cookie, false) === false) {
			echo "login failed";
			exit();
		}
	}

	// Let's make sure this user has the right permissions
	if ($jzUSER->getSetting("view") === false) {
		echo "must log in (user=x&pass=y)";
		exit();
	}

	// Let's create our services object
	// This object lets us do things like get metadata, resize images, get lyrics, etc
	$jzSERVICES = new jzServices();
	$jzSERVICES->loadStandardServices();
$display = new jzDisplay();

if (isset ($_GET['page'])) {
	   $args = $_GET;
	   unset($args['page']);
	   $func = 'jzApi_' . $_GET['page'];
	} else {
	  $args = array();
	  $func = 'jzApi_main';
	}

	// todo: make this a function; use Smarty
	// makes API flexible for various formats (json,xml,etc.)
	$res = $func($args);
header('Content-Type: application/xml');
echo "<mediaXML>\n";
        foreach ($res as $e) {
	  echo '<node name="'.xmlentities($e['name']) .'" ';
	  if (isset ($e['link'])) {
	    echo 'browse="'.xmlentities($e['link']).'" ';
	  }
	  if (isset($e['play'])) {
	    echo 'play="'.xmlentities($e['play']).'" ';
	  }
	  echo "/>\n";
	}
echo '</mediaXML>';



function xmlentities($string) {
  return str_replace ( array ( '&', '"', "'", '<', '>', '�' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), $string );
}














	/* Ex:
	 *
	 * Playlists
	 *   Sleepy
	 *   Party
	 * Genres
	 *   Alternative
	 *     Random Playlist
	 *     311
	 *     Alice in Chains
	 *     ....
	 *   Rock
	 *   Punk
	 *   Soft
	 * Random
	 *   New Album
	 *   Played Album
	 *   Random Album
	 * Options
	 *   Play on Chumby
	 *   Play on MPD Player
	 *   Build playlist
	 */
	function jzApi_main($argv) {
		$ret = array();

		//$ret[] = E("Playlists", null, "playlists");
		//$ret[] = E("Charts & Random", null, "random");
		$ret[] = E("Genres", null, "nodes");

		return $ret;
	}


	function jzApi_playlists($argv) {

	}

	function jzApi_random($argv) {

	}

	/* todo: make me better */
	function jzApi_nodes($argv) {
	  global $display;
		$ret = array();
		if (isset($argv["id"])) {
			$root = new jzMediaNode($argv["id"], "id");
		} else {
			$root = new jzMediaNode();
		}

		foreach ($root->getSubNodes("both") as $node) {
			if ($node instanceof jzMediaNode) {
				$ret[] = E($node->getName(),
					   ($node->getLevel() > 2) ? $node->getPlayHREF() : null,
					   "nodes",array("id" => $node->getID()));
			} else {
			  $ret[] = E($node->getName(),$node->getPlayHREF());
			}
		}

		return $ret;
	}


        function E($display_name, $playlink, $method = null, $args = array()) {

		$a = array('name'=>$display_name,'play'=>$playlink,'method'=>$method,'args'=>$args);

		if ($method != null) {
		  $link = $_SERVER['PHP_SELF'];
		  if (isset($_REQUEST['user']) && isset($_REQUEST['pass'])) {
		    $link .= '?user='.urlencode($_REQUEST['user']).'&pass='.urlencode($_REQUEST['pass']).'&';
		  }

		  $link .= 'page=' . $method;
		  foreach ($args as $key=>$val) {
		    $link .= '&' . urlencode($key) . '=' . urlencode($val);
		  }
		  $a['link']=$link;
		}

		return $a;
	}
?>