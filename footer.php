<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<footer class="footer">
	<?php if ($this->options->notice): ?>
	<p class="related">公告：<?php $this->options->notice() ?></p>
	<?php endif; ?>
	<p class="related"><a href="8" target="_blank">966966.xyz</a></p>
	<p class="related">&copy; <a href="/">966漫画</a></p>
	<?php if ($this->options->icp): ?>
	<p class="related"><a href="http://www.miitbeian.gov.cn/" target="_blank"><?php $this->options->icp() ?></a></p>
	<?php endif; ?>
	<?php if ($this->options->statistics): echo '<div style="display:none;">'; $this->options->statistics(); echo '</div>'; endif; ?>
</footer><!-- end #footer -->
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/masonry-docs.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('lightgallery/js/lightgallery.min.js'); ?>"></script>
<?php if (!empty($this->options->lightGalleryOpt) && in_array('lg_pager', $this->options->lightGalleryOpt)): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('lightgallery/js/lg-pager.min.js'); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->lightGalleryOpt) && in_array('lg_autoplay', $this->options->lightGalleryOpt)): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('lightgallery/js/lg-autoplay.min.js'); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->lightGalleryOpt) && in_array('lg_fullscreen', $this->options->lightGalleryOpt)): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('lightgallery/js/lg-fullscreen.min.js'); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->lightGalleryOpt) && in_array('lg_zoom', $this->options->lightGalleryOpt)): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('lightgallery/js/lg-zoom.min.js'); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->lightGalleryOpt) && in_array('lg_thumbnail', $this->options->lightGalleryOpt)): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('lightgallery/js/lg-thumbnail.min.js'); ?>"></script>
<?php endif; ?>
<script type="text/javascript">if(history.length < 2){$('.header-post-back').css('opacity', 0);}</script>
<script type="text/javascript">
<?php if ($this->is('post')) : ?>
	$(function() {
		//瀑布流
		var $container = $('#masonry');
		$container.imagesLoaded(function() {
			$container.masonry({
				itemSelector: '.post-item',
				gutter: 0,
				isAnimated: false,
			});
		});
		//灯箱
		var lg = document.getElementById('masonry');
		lightGallery(lg, {
			selector: '.post-item',
			download: false,
			enableTouch: true,
			pager: true,
		});
  });
<?php endif; ?>
</script>
<?php $this->footer(); ?>
</body>
</html>
