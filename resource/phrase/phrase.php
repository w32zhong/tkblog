<?php
function phraseFileHead($f_content)
{
	$f_content = ToLF($f_content);
	$pattern = "/^\t.+?\n/";//for compatibility of CRLF DOS & CR MAC & LF Unix
	$res_arr = array(); 
	$res_arr['tag'] = '未分类';//for default.
	
	while(1 == preg_match($pattern, $f_content, $matches))
	{
		$f_content = preg_replace($pattern,'',$f_content);
		
		preg_match("/^\t.+?:/",$matches[0],$submatches);
		$item_value = preg_replace("/^\t.+?:/",'',$matches[0]);
		$item_value = preg_replace("/\n/",'',$item_value);

		$item_name = preg_replace("/:/",'',$submatches[0]);
		$item_name = preg_replace("/\t/",'',$item_name);

		$res_arr[$item_name] = trim($item_value);
	}

	$res_arr['content'] = $f_content;
	return $res_arr;
}

function ToLF($str)
{
	$str = preg_replace("/\r\n/", "\n", $str);
	$str = preg_replace("/\r/", "\n", $str);
	return $str;
}

function IgnoreFileHead($f_content)
{
	$f_content = ToLF($f_content);
	return preg_replace("/^(\t.+?\n)*/",'',$f_content);
}

function phraseFileName($f_name)
{
	$place = './blog';
	$type = '';

	if(1 == preg_match('/^20.+\.txt$/',$f_name))
		$type = 'blog';
	else if(1 == preg_match('/^src-.+/',$f_name))
	{
		$type = 'src';
	}
	else if(1 == preg_match('/^com-.+\.txt$/',$f_name))
	{	
		$type = 'comment';
	}
	else
	{
		$place = './twbook/images';
		$type = 'old_src';
	}

	$id = 0;
	$toid = 0;
	$sub_id = 0;
	
	$year = -1;
	$month = -1;
	$day = -1;
	$hour = -1;
	$minute = -1;

	if($type != 'old_src')
	{
		preg_match_all("/[0-9]+/", $f_name, $matches);
		//print_r($matches);
		
		$year = intval($matches[0][0]);
		$month = intval($matches[0][1]);
		$day = intval($matches[0][2]);
		$hour = intval($matches[0][3]);
		$minute = intval($matches[0][4]);
		
		$place .= "/${year}/${month}";
		
		if($type == 'blog')
		{
			$id = intval($matches[0][5]);
		}
		else if($type == 'comment')
		{
			$toid = intval($matches[0][5]);
			$sub_id = intval($matches[0][6]);
		}
			
		$place .= "/${type}";
	}

	//            0     1     2      3    4    
	return array($type,$year,$month,$day,$hour,
//            5       6      7   8     9      10	
           $minute,$place,$id,$toid,$sub_id,$f_name);
}

function myNl2br($text)
{
	$order = array("\r\n", "\n", "\r");
	$replace = '<br />';
	return str_replace($order, $replace, $text);
}

function myBr2nl($text)
{
	return str_replace('<br />', "", $text);
}

function rmNl($text)
{
	$order = array("\r\n", "\n", "\r");
	$replace = '';
	return str_replace($order, $replace, $text);
}

function rmBrAtEnds($text)
{
	return preg_replace('/(^<br \/>)|(<br \/>$)/is', '', $text);
}

function multiline_tags_replace_callbk($matches)
{
	$preTag  = $matches[1];
	$postTag = $matches[3];

	return $preTag.myBr2nl($matches[2]).$postTag;
}
function rmBrTagInMultilineTags($text)
{
	$multiline_tags = array(
			'/(<ul>)(.+?)(<\/ul>)/s',
			'/(<ol>)(.+?)(<\/ol>)/s',
			'/(<table>)(.+?)(<\/table>)/s',
			'/(<table .+?>)(.+?)(<\/table>)/s'
			);
	
	$text = preg_replace_callback($multiline_tags, 
			'multiline_tags_replace_callbk', $text);
	return $text;
}

function myHtmlEntities($text)
{
	$text = htmlentities($text, ENT_NOQUOTES,'UTF-8');
	$text = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $text);
	$text = nl2br($text);
	
	return $text;
}

function replaceTagToTmpCallbk($matches)
{
	$preTag  = $matches[1];
	$preTag  = str_replace('<', '[[[--this-is-impossible', $preTag);
	$preTag  = str_replace('>', ']]]--this-is-impossible', $preTag);
	$preTag  = str_replace(' ', '___--this-is-impossible', $preTag);
	$postTag = $matches[3];
	$postTag = str_replace('<', '[[[--this-is-impossible', $postTag);
	$postTag = str_replace('>', ']]]--this-is-impossible', $postTag);
	$postTag = str_replace(' ', '___--this-is-impossible', $postTag);

	return $preTag.$matches[2].trim($postTag);
}

function RestoreReplacedTmpTags($text)
{
	$text = str_replace('[[[--this-is-impossible', '<', $text);
	$text = str_replace(']]]--this-is-impossible', '>', $text);
	$text = str_replace('___--this-is-impossible', ' ', $text);
	
	return $text;
}

function ReplaceAllowableHtmlTagsToTmp($text)
{
	$allowable_tags = array(
			'/(<a .+?>)(.+?)(<\/a>)/',
			'/(<embed .+?>)(.*?)(<\/embed>)/',
			'/(<embed )(.*?)(>)/',
			'/(<iframe .+?>)(.*?)(<\/iframe>)/',
			'/(<img )(.+?)(\/?>)/',
			'/(<em>)(.+?)(<\/em>)/s',
			'/(<pre>)(.+?)(<\/pre>)/s',
			'/(<i>)(.+?)(<\/i>)/s',
			'/(<ul>)(.+?)(<\/ul>)/s',
			'/(<ol>)(.+?)(<\/ol>)/s',
			'/(<li>)(.+?)(<\/li>)/s',
			'/(<b>)(.+?)(<\/b>)/s',
			'/(<strike>)(.+?)(<\/strike>)/s',
			'/(<sup>)(.+?)(<\/sup>)/s',
			'/(<sub>)(.+?)(<\/sub>)/s',
			'/(<h[1-6]>)(.+?)(<\/h[1-6]>)/s', 
			'/(<h[1-6] style=".+?">)(.+?)(<\/h[1-6]>)/s', 
			'/(<table>)(.+?)(<\/table>)/s',
			'/(<table .+?>)(.+?)(<\/table>)/s',
			'/(<tr>)(.+?)(<\/tr>)/s', 
			'/(<tr .+?>)(.+?)(<\/tr>)/s',
			'/(<th>)(.+?)(<\/th>)/s',
			'/(<th .+?>)(.+?)(<\/th>)/s',
			'/(<td>)(.+?)(<\/td>)/s',
			'/(<td .+?>)(.+?)(<\/td>)/s');
	//一般在使用类似ul的时候不习惯全写在一行，
	//而会为了美观加入换行。加入s模式以后，正则匹配
	//就不会被换行中断。
	
	$text = preg_replace_callback( $allowable_tags, 
			'replaceTagToTmpCallbk', $text);

	$text = str_replace("]]]--this-is-impossible\n[[[--this-is-impossible", 
	                   ']]]--this-is-impossible[[[--this-is-impossible', 
	                   $text);

	return $text;
}

function ReplaceAllowableHtmlTagsToTmp_comment($text)
{
	$allowable_tags = array(
			'/(<a .+?>)(.+?)(<\/a>)/',
			'/(<i>)(.+?)(<\/i>)/s',
			'/(<b>)(.+?)(<\/b>)/s',
			'/(<strike>)(.+?)(<\/strike>)/s');
	
	$text = preg_replace_callback( $allowable_tags, 
			'replaceTagToTmpCallbk', $text);
	
	$text = str_replace("]]]--this-is-impossible\n[[[--this-is-impossible", 
	                   ']]]--this-is-impossible[[[--this-is-impossible', 
	                   $text);

	return $text;
}

function ReplaceSelfDefTagsCallbk_code($matches)
{
	return '<script type="syntaxhighlighter" class="brush: cpp"><![CDATA['.
		myBr2nl($matches[2]).']]></script>';
}

function ReplaceSelfDefTagsCallbk_code_ext($matches)
{
	$lan_predicate = preg_match_all('/lan="(.*?)"/', $matches[1], $out, PREG_PATTERN_ORDER);
	if ($lan_predicate)
		$lan = $out[1][0];
	else
		$lan = "c";

	$hi_predicate = preg_match_all('/hi="(.*?)"/', $matches[1], $out, PREG_PATTERN_ORDER);
	if ($hi_predicate)
		$hi = $out[1][0];
	else
		$hi = "0";

	$fl_predicate = preg_match_all('/fl="(.*?)"/', $matches[1], $out, PREG_PATTERN_ORDER);
	if ($fl_predicate)
		$fl = $out[1][0];
	else
		$fl = "1";

	$gutter_predicate = preg_match_all('/ln="(.*?)"/', $matches[1], $out, PREG_PATTERN_ORDER);
	if ($gutter_predicate)
		$gutter = $out[1][0];
	else
		$gutter = "true";

	return '<script type="syntaxhighlighter" class="brush: '.$lan.
		'; highlight: ['.$hi.']; first-line: '.$fl.'; gutter: '.$gutter.
		';"><![CDATA['.myBr2nl($matches[2]).']]></script>';
}

function ReplaceSelfDefTags_code($text)
{
	$text = preg_replace_callback('/(\[code\])(.+?)(\[\/code\])(<br \/>)?/is',
			'ReplaceSelfDefTagsCallbk_code', $text);

	return preg_replace_callback('/(\[code[^\]]+\])(.+?)(\[\/code\])(<br \/>)?/is',
			'ReplaceSelfDefTagsCallbk_code_ext',$text);
}

function ReplaceSelfDefTagsCallbk_cmd($matches)
{
	$text = $matches[2];
	return '<pre class="cmd">'.myBr2nl($text).'</pre>';
}

function ReplaceSelfDefTags_cmd($text)
{
	return preg_replace_callback('/(\[cmd\])(.+?)(\[\/cmd\])(<br \/>)?/is',
			'ReplaceSelfDefTagsCallbk_cmd', $text);
}

function ReplaceSelfDefTagsCallbk_quote($matches)
{
	$preTag = '<blockquote class="quote">&nbsp;&nbsp;';
	$postTag = '</blockquote>';
	
	return $preTag.rmBrAtEnds($matches[2]).$postTag;
}

function ReplaceSelfDefTags_quote($text)
{
	return preg_replace_callback('/(\[quote\])(.+?)(\[\/quote\])(<br \/>)?/is',
			'ReplaceSelfDefTagsCallbk_quote', $text);
}

function ReplaceSelfDefTagsCallbk_diagram($matches)
{
	$preTag = '<div class="mermaid">';
	$postTag = '</div>';
	$midEle = str_replace('&#8209;', '-', $matches[2]);
	$midEle = html_entity_decode($midEle);
	$midEle = preg_replace('/(^<br \/>)|(<br \/>)/is', '', $midEle);
	return $preTag.$midEle.$postTag;
}

function ReplaceSelfDefTags_diagram($text)
{
	return preg_replace_callback('/(\[diagram\])(.+?)(\[\/diagram\])(<br \/>)?/is',
			'ReplaceSelfDefTagsCallbk_diagram', $text);
}

function ReplaceSelfDefTagsCallbk_face($matches)
{
	return '<img src="resource/leave_comment/faces/face'.$matches[2].'.gif"/>';
}

function ReplaceSelfDefTags_face($text)
{
	return preg_replace_callback('/(\[face\])(.+?)(\[\/face\])/i',
			'ReplaceSelfDefTagsCallbk_face', $text);
}

function ReplaceSelfDefTagsCallbk_key($matches)
{
	return '<span class="key">'.$matches[2].'</span>';
}

function ReplaceSelfDefTags_key($text)
{
	return preg_replace_callback('/(\[key\])(.+?)(\[\/key\])/i',
			'ReplaceSelfDefTagsCallbk_key', $text);
}

function ReplaceSelfDefTagsCallbk_kbd($matches)
{
	return '<span class="kbd">'.$matches[2].'</span>';
}
function ReplaceSelfDefTags_kbd($text)
{
	return preg_replace_callback('/(\[kbd\])(.+?)(\[\/kbd\])/i',
			'ReplaceSelfDefTagsCallbk_kbd', $text);
}

function photo_html_tag($img_path)
{
	error_reporting(E_ERROR | E_PARSE);// turn off warnings
	$img_size = getimagesize($img_path) ?: array(0, 0);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);// turn on warnings again

	if ($img_size[0] == 0)
		$img_size = getimagesize("../../".$img_path) ?: array(0, 0);

	//if greater than CSS img max-width, adjust it
	if ($img_size[0] >= 630)
		$img_size[0] = 630;

	return '<div style="width:'.$img_size[0].'px;" class="photo_frame shadow">'.'<img src="'.$img_path.'"/></div>';
}

function ReplaceSelfDefTagsCallbk_photo($matches)
{
	return photo_html_tag($matches[2]);
}

function ReplaceSelfDefTags_photo($text)
{
	return preg_replace_callback('/(\[photo\])(.+?)(\[\/photo\])/i',
			'ReplaceSelfDefTagsCallbk_photo', $text);
}

function ReplaceSelfDefTagsCallbk_overline($matches)
{
	return '<span style="text-decoration:overline;">'.$matches[2].'</span>';
}

function ReplaceSelfDefTags_overline($text)
{
	return preg_replace_callback('/(\[overline\])(.+?)(\[\/overline\])/i',
			'ReplaceSelfDefTagsCallbk_overline', $text);
}

function ReplaceSelfDefTagsCallbk_underline($matches)
{
	return '<span style="text-decoration:underline;">'.$matches[2].'</span>';
}

function ReplaceSelfDefTags_underline($text)
{
	return preg_replace_callback('/(\[underline\])(.+?)(\[\/underline\])/i',
			'ReplaceSelfDefTagsCallbk_underline', $text);
}

function ReplaceSelfDefTagsCallbk_link($matches)
{
	return '<a href="'.$matches[2].'" target="_blank">'.$matches[2].'</a>';
}

function ReplaceSelfDefTags_link($text)
{
	return preg_replace_callback('/(\[link\])(.+?)(\[\/link\])/i',
			'ReplaceSelfDefTagsCallbk_link', $text);
}

function ReplaceSelfDefTagsCallbk_at($matches)
{
	preg_match_all('/([0-9]+)_([0-9]+)/', $matches[2], $out, PREG_PATTERN_ORDER);
	$to_id  = $out[1][0];
	$sub_id = $out[2][0];

	$comment = dbGetCommentNameAndEmailByID($to_id, $sub_id);

	$str = '<a href="#comment_id_'.$to_id.'_'.$sub_id.'">';
	
	$name = $comment['name'];
	if($name == 't.k.ga6840')
		$name = 't.k.';

	$str .= '@'.$name.'</a>&nbsp;';

	return $str;
}

function ReplaceSelfDefTags_at($text)
{
	return preg_replace_callback('/(\[at\])(.+?)(\[\/at\])/i',
			'ReplaceSelfDefTagsCallbk_at', $text);
}


function SearchAtTagsAndSendMail($text, $url)
{
	$num_found = preg_match_all('/\[at\]([0-9]+)_([0-9]+)\[\/at\]/i', 
				$text, $out, PREG_PATTERN_ORDER);

	for($i=0; $i<$num_found; $i++)
	{
		$to_id  = $out[1][$i];
		$sub_id = $out[2][$i];
		$comment = dbGetCommentNameAndEmailByID($to_id, $sub_id);
		$mail_notify = $comment['mail_notify'];
		$email = $comment['email'];

		MySend($email, $url, $mail_notify);
	}
}

function rmCut_more(&$content)
{
	$content = str_replace('[cut_more]', '--------->8---------', $content);
}

function doBlogExcerpt(&$content)
{
	if(preg_match('/(^.+)\[cut_more\]/is', $content, $matches))
	{
		$content = $matches[1];
		return true;
	}
	else
		return false;
}

function getBlogMoreContent($whole_content)
{
	if(preg_match('/\[cut_more\](.+$)/is', $whole_content, $matches))
	{
		return $matches[1];
	}
	else
		return 'EMPTY';
}

function restoreHyphenInMathjax($content)
{
	//restore dmath
	$content_array = preg_split('/(\[dmath\].*?\[\/dmath\])/is', $content,
			-1, PREG_SPLIT_DELIM_CAPTURE);
	$res_content = '';

	foreach( $content_array as $i => $content_piece)
	{
		if($i % 2 == 0)
			$res_content .= $content_piece;
		else
			$res_content .= str_replace('&#8209;', '-', $content_piece);
	}
	
	//restore imath 
	$content = $res_content;

	$content_array = preg_split('/(\[imath\].*?\[\/imath\])/is', $content,
			-1, PREG_SPLIT_DELIM_CAPTURE);
	$res_content = '';

	foreach( $content_array as $i => $content_piece)
	{
		if($i % 2 == 0)
			$res_content .= $content_piece;
		else
			$res_content .= str_replace('&#8209;', '-', $content_piece);
	}

	return $res_content;
}

function useNonBreakingHyphen($content)
{
	//it is a little complecated for writing so much code just
	//to replace the '-' to '&#8209;'. But if we want to make sure
	//the '-' in HTML tags are not be placed, we need to do so.

	$content_array = preg_split('/(<.*?>)/is', $content,
			-1, PREG_SPLIT_DELIM_CAPTURE);
	$res_content = '';

	foreach( $content_array as $i => $content_piece)
	{
		if($i % 2 == 0)
		{
			//using the non-breaking hyphen:
			#$tmp_str = str_replace("-", "&#8209;", $content_piece);
			$tmp_str = $content_piece;

			//do not use the HTML space, it won't break lines
			//$res_content .= str_replace(" ","&nbsp;",$tmp_str);
			$res_content .= $tmp_str;
		}
		else
			$res_content .= $content_piece;
	}

	return $res_content;
}

function phraseBlogContent($blog_content)
{
	$blog_content = ReplaceAllowableHtmlTagsToTmp($blog_content);
	$blog_content = myHtmlEntities($blog_content);
	$blog_content = RestoreReplacedTmpTags($blog_content);
	$blog_content = rmBrTagInMultilineTags($blog_content);

	$blog_content = ReplaceSelfDefTags_code($blog_content);
	$blog_content = ReplaceSelfDefTags_cmd($blog_content);
	$blog_content = ReplaceSelfDefTags_quote($blog_content);
	$blog_content = ReplaceSelfDefTags_face($blog_content);
	$blog_content = ReplaceSelfDefTags_key($blog_content);
	$blog_content = ReplaceSelfDefTags_photo($blog_content);
	$blog_content = ReplaceSelfDefTags_overline($blog_content);
	$blog_content = ReplaceSelfDefTags_underline($blog_content);
	$blog_content = ReplaceSelfDefTags_link($blog_content);
	$blog_content = ReplaceSelfDefTags_kbd($blog_content);
	$blog_content = str_replace('[page_break]', '<div class="pagebreak"></div>', $blog_content);
	$blog_content = useNonBreakingHyphen($blog_content);

	$blog_content = ReplaceSelfDefTags_diagram($blog_content);
	
	$blog_content = restoreHyphenInMathjax($blog_content);
	//so that the minus sign '-' in Mathjax will not be too small

	return $blog_content;
}

function phraseCommentContent($content)
{
	$content = ReplaceAllowableHtmlTagsToTmp_comment($content);
	$content = myHtmlEntities($content);
	$content = RestoreReplacedTmpTags($content);
	$content = ReplaceSelfDefTags_face($content);
	$content = ReplaceSelfDefTags_at($content);
	$content = ReplaceSelfDefTags_key($content);
	$content = useNonBreakingHyphen($content);

	return $content;
}
?>
