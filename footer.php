<footer id="metro-footer">
<?php wp_nav_menu( array( 'theme_location' => 'footer_nav', 'container_class'=>'footer-nav', 'depth'=>'1' ) ); ?>

<div class="copyright"><?php echo get_theme_mod( 'copyright_textbox', get_bloginfo('name') ); ?></div>
<?php wp_footer();?>

</footer>

<?php
echo metro_loader();
?>
</div>	<!-- end #metro-pjax -->
</body>
</html>