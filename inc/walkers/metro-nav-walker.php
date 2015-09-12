<?php
class metro_main_nav_walker extends Walker_Nav_Menu
{
  function start_lvl( &$output, $depth = 0, $args = array() ) {
      $indent = str_repeat("\t", $depth);
      $output .= "\n$indent<div class='sub-menu-wrap'><ul class='sub-menu'>\n";
  }
  function end_lvl( &$output, $depth = 0, $args = array() ) {
      $indent = str_repeat("\t", $depth);
      $output .= "$indent</ul></div>\n";
  }

  function start_el(&$output, $item, $depth, $args)
  {
       global $wp_query;
       $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

       $class_names = $value = '';

       $classes = empty( $item->classes ) ? array() : (array) $item->classes;

       $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
       $class_names = ' class="'. esc_attr( $class_names ) . '"';

       $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

      /* Get some useful things */
      $target_id = url_to_postid($item->url);
      $animation_data = metro_get_animation_data($target_id, false, false, $item->url);


       $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
       $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
       $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
       $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
       $attributes .= ($item->notransition == 'on' || $animation_data == '')  ? ' class=" notransition " ' : $animation_data;

       $prepend = '';
       $append = '';
       $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

       if($depth != 0)
       {
        $description = $append = $prepend = "";
       }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
        $item_output .= $description.$args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}
?>