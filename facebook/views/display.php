<ul>
	<?php foreach($posts as $post): ?>
	<li>
		<?php echo $post['message']; ?>
	    <date><?php echo format_date($post['posted'], Settings::get('date_format').' h:i'); ?></date>
	</li>
	<?php endforeach; ?>
</ul>