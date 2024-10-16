# RequestHandler component

Load e.g. using
```php
$options = ['viewClassMap' => ['rss' => 'Feed.Rss'], 'enableBeforeRedirect' => false];
$this->loadComponent('Shim.RequestHandler', $options);
```

## Auto AJAX Layout switching
One of the useful functionality of this component is the globally available auto switch for e.g. AJAX
layout (= none), rendering out the same page without the surrounding layout.

This can be useful when rendering AJAXified pagination.
```html
<?php $this->append('script');?>
<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click', 'div.paging a', function() {
		$('div#messageContainer').fadeTo(300, 0);

		var thisHref = $(this).attr("href");

		$('div#messageContainer').load(thisHref, function() {
			$(this).fadeTo(200, 1);
			$('html, body').animate({
				scrollTop: $("#messageContainer").offset().top
			}, 200);
		});
		return false;
	});
});
</script>
<?php $this->end();?>
```
With the RequestHandler this works out of the box with the same template file(s).
