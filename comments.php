<?php
if(!empty($_SERVER["SCRIPT_FILENAME"]) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die("Please do not load this page directly. Thanks");

if( post_password_required()){?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php 
	return;
}
?>
<section id ="comments">
<h3><?php comments_number('No Comments','1 Comment', '% Comments' );?></h3>
<?php if(have_comments()) : ?>
	<?php wp_list_comments(array("walker"=>new metro_walker_comment(), "avatar_size"=>64) );?>
	<?php if (get_comment_pages_count() >1):?>
		<div class="pagination">
			<ul class="metro-anim-ul">
				<li class="older"><?php previous_comments_link("Older");?></li>
				<li class="newer"><?php next_comments_link("Newer" );?></li>
			</ul>
		</div>
	<?php 
	endif;
endif;
?>
</section>
<section id="respond">
		<?php if ( comments_open()):?>
		<h3>Leave a response</h3>
		<form action = "<?php echo get_option("siteurl");?>/wp-comments-post.php" method="post" id = "comment-form">
			<fieldset>
				<table width="100%">
				<tr>
				<td width="75"><label for="author">Name:</label></td>
				<td><input type="text" name = "author" id = "author" value="<?php echo $comment_author;?>" /></td>
				</tr>
				<tr>
				<td><label for="email">Email:</label></td>
				<td><input type="text" name = "email" id = "email" value="<?php echo $comment_author_email;?>" /></td>
				</tr>
				<tr>
				<td><label for="url">Website:</label></td>
				<td><input type="text" name = "url" id = "url" value="<?php echo $comment_author_url;?>" /></td>
				</tr>
				<tr>
				<td><label for="comment">Message:</label></td>
				<td><textarea name = "comment" id = "respond-textarea" rows="" cols = ""></textarea></td></tr>
				<tr><td></td><td>
				<input type="submit" class="commentsubmit" value = "Reply"/>
				</td></tr>
				</table>

				<?php comment_id_fields( );?>
				<?php do_action('comment_form', $post->ID );?>

			</fieldset>
		</form>
		<p class="cancel"><?php cancel_comment_reply_link('Cancel Reply' );?></p>
		</div>
	<?php else:?>
		<h3> Comments are closed now</h3>
<?php endif;?>
</section>
