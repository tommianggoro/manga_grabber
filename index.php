<?php 

include 'simple_html_dom.php';
require_once 'list_manga.php';

$mangaList = mangaList();
set_time_limit(0);
foreach ($mangaList as $web => $listManga) {
	foreach($listManga as $title => $urlManga){
		$dataHtml = file_get_html($urlManga);
		if(empty($dataHtml)){
			continue;
		}
		switch ($web) {
			case 'mangakakalot':
				mangakakalot($dataHtml, $title);
				break;
			case 'mangakita':
				mangakita($dataHtml, $title);
			default:
				echo 'Site not registered';
				break;
		}
	}
}

function mangakakalot($dataHtml = false, $title = ''){
	$rowChapterList = $dataHtml->find('div.chapter-list > div.row > a');
	if(empty($rowChapterList)){
		return false;
	}
	foreach ($rowChapterList as $valueChapterList) {
		$urlDetail = $valueChapterList->href;
		$chapter = explode('/', $urlDetail);
		$chapter = end($chapter);

		$chapterTitle = str_replace(array(':','"'),'-',$valueChapterList->innertext);
		
		$dataDetailHtml = file_get_html($urlDetail);
		$dataDetail = $dataDetailHtml->find('div#vungdoc > img');
		if(empty($dataDetail)){
			continue;
		}
		echo 'Saving '.$chapterTitle.' : <br/>';
		foreach ($dataDetail as $detail) {
			$filename = preg_replace('/[^\da-z]/i', '_', $detail->title);
			$ext = explode('.', $detail->src);
			$ext = end($ext);

			$dir = 'data/'.$title.'/'.$chapterTitle.'/';
			if(!file_exists($dir)){
				mkdir($dir, 0777, true);
			}
			if(@copy($detail->src, $dir.$filename.'.'.$ext)){
				echo $filename.' saved to : '.$dir.$filename.'<br/>';
			}else{
				echo $filename.' failed to save<br/>';
			}
		}
		echo '==========================END SAVING=========================<br/>';
	}
}

function mangakita($dataHtml = false, $title = ''){
	$rowChapterList = $dataHtml->find('div#content > div.cl > ul > li > a');
	if(empty($rowChapterList)){
		return false;
	}
	foreach ($rowChapterList as $rowChapter) {
		$chapterTitle = str_replace(array(':','"'),'-',$rowChapter->innertext);
		$urlDetail = $rowChapter->href;
		
		$detailHtml = file_get_html($urlDetail);
		$imageList = $detailHtml->find('article > div#readerarea > img');
		if(empty($imageList)){
			continue;
		}
		echo 'Saving '.$chapterTitle.' : <br/>';
		foreach($imageList as $image){
			$filename = explode('/', $image->src);
			$filename = end($filename);

			$dir = 'data/'.$title.'/'.$chapterTitle.'/';
			if(!file_exists($dir)){
				mkdir($dir, 0777, true);
			}
			if(@copy($image->src, $dir.$filename)){
				echo $filename.' saved to : '.$dir.$filename.'<br/>';
			}else{
				echo $filename.' failed to save<br/>';
			}
		}
		echo '==========================END SAVING=========================<br/>';
	}
}

?>