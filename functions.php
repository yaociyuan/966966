<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form) {
	$icp = new Typecho_Widget_Helper_Form_Element_Text('icp', NULL, NULL, _t('大天朝身份认证'), _t('填写 ICP 备案号，留空则不显示。'));
	$form->addInput($icp);
	$notice = new Typecho_Widget_Helper_Form_Element_Textarea('notice', NULL, NULL, _t('网站公告'), _t('填写网站公告，留空则不显示。'));
	$form->addInput($notice);
	$statistics = new Typecho_Widget_Helper_Form_Element_Textarea('statistics', NULL, NULL, _t('统计代码'), _t('填写统计平台生成的统计代码，该内容在页面隐藏生效，留空则不生效。'));
	$form->addInput($statistics);
	$picdesc = new Typecho_Widget_Helper_Form_Element_Textarea('picdesc', NULL, NULL, _t('组图默认描述'), _t('填写组图的默认描述，优先级低于“自定义字段”的值，留空则显示“未填写”。'));
	$form->addInput($picdesc);
	$lightGalleryOpt = new Typecho_Widget_Helper_Form_Element_Checkbox('lightGalleryOpt', 
	array('lg_pager' => _t('页码指示器'),
	'lg_autoplay' => _t('自动播放功能'),
	'lg_fullscreen' => _t('全屏功能'),
	'lg_zoom' => _t('缩放功能'),
	'lg_thumbnail' => _t('缩略图列表（建议与页码指示器互斥使用）')),
	array('lg_pager', 'lg_autoplay', 'lg_fullscreen', 'lg_zoom', 'lg_thumbnail'), _t('lightGallery 功能开关'));
	$form->addInput($lightGalleryOpt->multiMode());
}

//文章缩略图 (废弃)
function showThumb($obj, $link = false) {
    preg_match_all( "/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/", $obj->content, $matches);
    $thumb = '';
    $options = Typecho_Widget::widget('Widget_Options');
    $attach = $obj->attachments(1)->attachment; 
    if (isset($attach->isImage) && $attach->isImage == 1) {
        $thumb = $attach->url;   //附件是图片 输出附件
    } elseif (isset($matches[1][0])) {
        $thumb = $matches[1][0];  //文章内容中抓到了图片 输出链接
    }
	//空的话输出默认随机图
	$thumb = empty($thumb) ? $options->themeUrl .'/img/' . rand(1, 14) . '.jpg' : $thumb;	
    if($link) {
        return $thumb;
    }
	else {
		$thumb='<img src="'.$thumb.'">';
		return $thumb;
	}
}

//获取附件图片v1 (废弃)
function getAttachImg($cid) {
	$db = Typecho_Db::get();
	$rs = $db->fetchAll($db->select('table.contents.text')
			->from('table.contents')
			->where('table.contents.parent=?', $cid)
			->order('table.contents.cid', Typecho_Db::SORT_ASC));
	$attachPath = array();
	foreach($rs as $attach) {
		$attach = unserialize($attach['text']);
		if($attach['mime'] == 'image/jpeg') {
			$attachPath[] = array($attach['name'], $attach['path']);
		}
    }
	return $attachPath;
}

//获取文章附件图
function getPostAttImg($obj) {
	$stack = $obj->attachments()->stack;
	$atts = array();
	for($i = 0; $i < count($stack); $i++) {
		$att = $stack[$i]['attachment'];
		if($att->isImage) {
			$atts[] = array('name' => $att->name, 'url' => $att->url);
        }
	}
	return $atts;
}

//获取文章内容图
function getPostHtmImg($obj) {
	preg_match_all( "/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/", $obj->content, $matches);
	$atts = array();
	if(isset($matches[1][0])) {
		for($i = 0; $i < count($matches[1]); $i++) {
			$atts[] = array('name' => $obj->title.' ['.($i + 1).']', 'url' => $matches[1][$i]);
		}
    }
	return  count($atts) ? $atts : NULL;
}

//获取文章图片 整合 getPostAttImg() 与 getPostHtmImg()
function getPostImg($obj) {
	$imgs = array();
	if($obj->fields->src == 0) {
		$imgs = getPostHtmImg($obj);
	}elseif($obj->fields->src == 1) {
		$imgs = getPostAttImg($obj);
	}elseif($obj->fields->src == 2) {
		$imgs = array_merge(getPostHtmImg($obj), getPostAttImg($obj));
	}
	return $imgs;
}

//后期软件
function afterSoftware() {
	return array(
		_t('未知'),
		_t('Photoshop'),
		_t('Google Picasa'),
		_t('Snapseed'),
		_t('泼辣修图'),
		_t('美图秀秀'),
		_t('Camera 360'),
		_t('天天P图'),
		_t('黄油相机'),
		_t('Enlight'),
		_t('Facetune'),
		_t('Prisma'),
		_t('PicsArt'),
		_t('Pixlr'),
		_t('VSCO'),
		_t('Instagram'),
    );
}

//自定义字段
function themeFields($layout) {
	$title = new Typecho_Widget_Helper_Form_Element_Select('title', array(_t('文章标题序列'), _t('图片文件名或描述')), NULL, _t('图片[title]'), _t('选择前台图片标签的 title 属性值（图片源为内容的 [title] 默认为文章标题序列）'));
	$layout->addItem($title);
	$src = new Typecho_Widget_Helper_Form_Element_Select('src', array(_t('内容'), _t('附件'), _t('附件+内容')), NULL, _t('图片源'), _t('选择前台展示的图片源（若选择[附件+内容]则内容图片在前）'));
	$layout->addItem($src);
	$photog = new Typecho_Widget_Helper_Form_Element_Text('photog', NULL, NULL, _t('作者/来源'), _t('在这里填写拍摄照片者的姓名'));
	$layout->addItem($photog);
	$srcurl = new Typecho_Widget_Helper_Form_Element_Text('srcurl', NULL, NULL, _t('来源地址'), _t('在这里填写图片出处的网络地址（留空则不链接地址）'));
	$layout->addItem($srcurl);
	$appear = new Typecho_Widget_Helper_Form_Element_Text('appear', NULL, NULL, _t('出镜人物'), _t('在这里填写照片出镜者的姓名'));
	$layout->addItem($appear);
	$software = new Typecho_Widget_Helper_Form_Element_Select('software', afterSoftware(), NULL, _t('处理软件'), _t('在这里选择照片后期处理软件'));
	$layout->addItem($software);
	$description = new Typecho_Widget_Helper_Form_Element_Textarea('description', NULL, NULL, _t('图集描述'), _t('在这里填写照片描述等其他文本信息'));
	$layout->addItem($description);
	$thumb = new Typecho_Widget_Helper_Form_Element_Text('thumb', NULL, NULL, _t('封面图片'), _t('在这里填写封面图片的地址（留空将自动获取第一个附件图片）'));
	$layout->addItem($thumb);
}


//以下函数未启用
function themeInit($archive){
    if ($archive->is('single')){
    		//$archive->content = image_class_replace($archive->content);
    }
}

function image_class_replace($content){
    $content = preg_replace('#<img(.*?) src="([^"]*/)?(([^"/]*)\.[^"]*)"(.*?)>#', '<div class="post-item layui-col-xs6 layui-col-sm4 layui-col-md3"><img$1 src="$2$3"$5 class="post-item-img"></div>', $content);
    return $content;
}