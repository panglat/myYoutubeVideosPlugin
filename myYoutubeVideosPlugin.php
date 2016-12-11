<?php
/**
 * plugin blankContentPlugin
 * @version 1.2
 * @package blankContentPlugin
 * @copyright Copyright (c) Jahr Firmennamen URL
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

/**
 * Platz für Informationen
 * =======================
 *
 * Anwendung im Content:
 *   {MyYoutubeVideosPlugin}
 *
 * Anwendung im Content mit Parameterübergabe:
 *   {MyYoutubeVideosPlugin param1=Hello!|param2=it works fine|param3=Joomla! rocks ;-)}
 */


defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');


class plgContentMyYoutubeVideosPlugin extends JPlugin {
	
	function plgContentMyYoutubeVideosPlugin( &$subject ) {
		parent::__construct( $subject );
	}

	public function getParam($param, $text, $default=null) {
		if(preg_match_all("/".$param."='(.*?)'/", trim($text), $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				return $match[1];
			}
		}
		return $default;
	}

	public function getEmbedYouTuveHTML($youtubeId) {
		return 
			"<object width='640' height='390'>\n".
			"  <param name='movie'\n".
			"         value='https://www.youtube.com/v/".$youtubeId."?version=3'></param>\n".
			"  <param name='allowScriptAccess' value='always'></param>\n".
			"  <embed src='https://www.youtube.com/v/".$youtubeId."?version=3'\n".
			"         type='application/x-shockwave-flash'\n".
			"         allowscriptaccess='always'\n".
			"         width='640' height='390'></embed>\n".
			"</object>\n";
	}

    public function getEmbedYouTuvTableHTML($youtubeId, $description) {
        $output = "<table style='background-color: #333333; border-color: #ffffff; border-width: 0px;' align='center' border='0' cellpadding='8'>\n";
        $output .= "<tr><td>";
        $output .= $this->getEmbedYouTuveHTML($youtubeId);
        $output .= "</td></tr>";
        if($description != null) {
            $output .= "<tr><td style='text-align: center;'><p><strong><span style='color: #ffffff;'>".$description."</span></strong></p></td></tr>";
        }
        $output .= "</table>\n";
        
        return $output;
	}


  /**
   * Contentstring Definition
   * String erkennen und mit neuem Inhalt füllen
   */
  public function onContentPrepare($context, &$article, &$params, $limitstart) {
      // simple performance check to determine whether bot should process further
	  if (strpos($article->text, 'MyYoutubeVideosPlugin') === false) {
		  return true;
      }
	  $regex = '/{MyYoutubeVideosPlugin\s*(.*?)}/i';
	  $article->text = preg_replace_callback($regex,array($this,"form"), $article->text);
	  return true;
  }


  public function form($matches) {
      $output = "";
      $description = $this->getParam("description",$matches[1]);
      $photoId = $this->getParam("id",$matches[1]);
      if($photoId != null) {
          $output = $this->getEmbedYouTuvTableHTML($photoId, $description);
      }
      return $output;
	}

}

?>