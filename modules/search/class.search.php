<?php

class Jetpack_Search {

	/**
	 * Set up our filters and actions.
	 */
	public static function do_the_things() {
		add_action( 'init',          array( __CLASS__, 'register_scripts_styles' ) );
		add_filter( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
	}

	/**
	 * Register scripts and styles to be used later.
	 */
	public static function register_scripts_styles() {
		$script_version = Jetpack::is_development_version() ? filemtime( dirname( __FILE__ ) . '/search.js' ) : JETPACK__VERSION;
		wp_register_script( 'jetpack-search', plugins_url( 'search.js', __FILE__ ), array( 'jquery' ), $script_version );


		$style_version = Jetpack::is_development_version() ? filemtime( dirname( __FILE__ ) . '/search.css' ) : JETPACK__VERSION;
		wp_register_style( 'jetpack-search', plugins_url( 'search.css', __FILE__ ), array(), $style_version );
	}

	/**
	 * Runs on the `pre_get_posts` filter.  Shorts out the main query search.
	 *
	 * @param $query The WP_Query
	 *
	 * @return mixed The WP_Query
	 */
	public static function pre_get_posts( $query ) {
		if ( $query->is_main_query() && $query->is_search() ) {
			$query->set( 'post__in', array( 0 ) );
			add_filter( 'the_posts', array( __CLASS__, 'the_posts' ), 10, 2 );
			add_filter( 'template_include', 'get_index_template' );
		}
		return $query;
	}

	/**
	 * Filter the posts so that we can return a dummy post.
	 *
	 * @param $posts
	 * @param $query
	 *
	 * @return array
	 */
	public static function the_posts( $posts, $wp_query ) {
		remove_filter( 'the_posts', array( __CLASS__, 'the_posts' ), 10 );

		// If this isn't the main query, do nothing.
		if ( ! $wp_query->is_main_query() ) {
			return $posts;
		}

		// Add the shortcode and filters to let it work in excerpts.
		add_shortcode( 'jetpack-search-template', array( __CLASS__, 'shortcode' ) );
		add_filter( 'the_excerpt', 'do_shortcode' );

		$post = (object) array(
			'post_title'     => '',
			'post_content'   => '[jetpack-search-template]',
			'post_excerpt'   => '[jetpack-search-template]',
			'post_date'      => current_time( 'mysql' ),
			'comment_status' => 'closed',
		);
		$posts = array( $post );

		return $posts;
	}

	/**
	 * The shortcode processor for the `[jetpack-search-template]` shortcode.
	 * @return string
	 */
	public static function shortcode() {
		wp_enqueue_style( 'jetpack-search' );
		wp_enqueue_script( 'jetpack-search' );
		wp_localize_script( 'jetpack-search', 'jetpackSearchData', array(
			'initialSearchTerm' => get_search_query( false ), // Unescaped, must be escaped in JS.
		) );

		ob_start();

		?>
		<div id="jetpack-search-root">
			<?php echo get_search_form(); ?>
			<h2><?php esc_html_e( 'Search Results', 'jetpack' ); ?></h2>
			<div class="search-results">
				<p><?php _e( 'Loading<span class="ellipses animate">…</span>', 'jetpack' ); ?></p>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}