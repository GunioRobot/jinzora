<?php if (!defined(JZ_SECURE_ACCESS)) die ('Security breach detected.');
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
	* - Creates an M3U compliant playlist
	*
	* @since 02.17.05
	* @author Ben Dodson <ben@jinzora.org>
	* @author Ross Carlson <ross@jinzora.org>
	*/

	/**
	* Returns the mime type for this playlist
	*
	* @author Ross Carlson
	* @version 2/24/05
	* @since 2/24/05
	* @param $return Returns the playlist mime type
	*/
	function SERVICE_RETURN_MIME_PLAYER(){
		return "";
	}

	/**
	* Creates an M3U compliant playlist and returns it for playing
	*
	* @author Ross Carlson
	* @version 2/24/05
	* @since 2/24/05
	* @param $list The list of tracks to use when making the playlist
	* @param $return Returns the porperly formated list
	*/
	function SERVICE_CREATE_PLAYLIST_PLAYER($list){
		global $allow_resample;

		// Let's start the list off right
		$list->flatten();
		// Now let's loop throught the items to create the list
		foreach ($list->getList() as $track) {
			if (stristr($track->getPath("String"),".lofi.") and stristr($track->getPath("String"),".clip.")){continue;}
			$trackn = $track->getFileName("general");

			if (!stristr($trackn,"mediabroadcast.php")) {
			  $track->increasePlayCount();
			}
			$content[] = $trackn;
		}

		// Now let's return
		return $content;
	}
?>