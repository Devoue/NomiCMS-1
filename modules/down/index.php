<?php

define('R', $_SERVER['DOCUMENT_ROOT']);
define('S', R.'/system');

require_once(R.'/system/kernel.php');

$tmp->header('zc');
$tmp->title('title', Language::config('zc'). (User::level() >= 3 ? '<span><a href="/zc/?d">'.img('add_c.png').'</a></span>' : NULL));
User::panel();

$id = (empty($id) ? null : my_int($_GET['d']));

if(User::aut()){
	if(isset($_GET['d'])){
		if(User::level() >= 3){

			if(isset($_REQUEST['submit'])){
				$name = $db->guard($_POST['name']);
				if(mb_strlen($_POST['name'], 'UTF-8')<2) $error .= Language::config('no_message');
				
				if(!isset($error)) {
					$db->query("insert into `zc_category` set `name` = '".$name."', `opis` = '', `time` = '".time()."' ");
					header('location: /zc');
				}
			}

		error($error);

		$tmp->div('main', '<form method="POST" action="">
'.Language::config('name').':<br/>
<input name="name" value="'.out($_POST['name']).'" /><br />
<input type="submit" name="submit" value="'.Language::config('add').'" /></form>');
		$tmp->div('menu', '<hr><a href="/zc">'.img('link.png').' '.Language::config('back').'</a>');
		$tmp->footer();
   		exit();
		}
	}
}

$posts=$db->fass_c("SELECT COUNT(*) as count FROM `zc_category`");

if($posts==0){
	$tmp->div('main', Language::config('no_libl_category'));
	$tmp->footer();
	exit();
}

$total = (($posts-1)/$num)+1;
$total = intval($total);
$page = intval($page);
if(empty($page) or $page<0) $page=1;
if($page>$total) $page=$total;
$start=$page*$num-$num;

$zc=$db->query("SELECT * FROM `zc_category` ORDER BY id ASC LIMIT ".$start.", ".$num." ");

echo '<div class="menu">';
while($z=$zc->fetch_assoc()){
	echo '<hr><a href="/zc/cat'.$z['id'].'">'.img('ct.png').' '.$z['name'].'</a>';
}
echo '</div>';

page('?');

$tmp->div('menu', '<hr><a href="/">'.img('link.png').' '.Language::config('home').'</a>');
$tmp->footer();
?>