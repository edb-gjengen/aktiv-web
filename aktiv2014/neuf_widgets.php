<?php
class NeufTodaysEventsWidget extends WP_Widget {

	function NeufTodaysEventsWidget() {
		// Instantiate the parent object
		parent::__construct(
			false,
			'Dagens program',
			array( 'description' => 'Fra studentersamfundet.no')
		);
	}

	function widget( $args, $instance ) {
		echo $args['before_widget'];
		$title = 'Dagens program';
		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		$html = '<div class="events-today-wrap">' .
        '<h2>'.$title.'</h2>' .
        '<div class="events-today">' .
        '    <ul class="program-list"><!-- JS magic --></ul>' .
        '    <a class="events-today-link-all" href="https://studentersamfundet.no/program/">Vis hele programmet</a>' .
        '</div>' .
    	'</div>';
    	echo $html;;
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}
}

function neuf_register_widgets() {
	register_widget( 'NeufTodaysEventsWidget' );
}

add_action( 'widgets_init', 'neuf_register_widgets' );
?>