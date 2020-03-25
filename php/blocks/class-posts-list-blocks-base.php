<?php
/**
 * Base class to allow sharing functionality between blocks listing posts
 * such as Recent Posts and Hand-picked posts blocks.
 *
 * @package MaterialThemeBuilder
 */

namespace MaterialThemeBuilder\Blocks;

use MaterialThemeBuilder\Module_Base;
use MaterialThemeBuilder\Plugin;
use MaterialThemeBuilder\Template;
use WP_Post;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Posts_List_Blocks_Base class.
 */
class Posts_List_Blocks_Base extends Module_Base {
	/**
	 * Block name.
	 *
	 * @var string
	 */
	public $block_name;

	/**
	 * Block core attributes.
	 *
	 * @var array
	 */
	public $block_core_attributes;

	/**
	 * Block extra attributes.
	 *
	 * @var array
	 */
	public $block_extra_attributes;

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		$this->block_core_attributes = [
			'className'             => [
				'type' => 'string',
			],
			'style'                 => [
				'type'    => 'string',
				'default' => 'masonry',
			],
			'align'                 => [
				'type'    => 'string',
				'default' => '',
			],
			'contentLayout'         => [
				'type'    => 'string',
				'default' => 'text-above-media',
			],
			'columns'               => [
				'type'    => 'number',
				'default' => 3,
			],
			'postsToShow'           => [
				'type'    => 'number',
				'default' => 12,
			],
			'outlined'              => [
				'type'    => 'boolean',
				'default' => false,
			],
			'displayPostDate'       => [
				'type'    => 'boolean',
				'default' => true,
			],
			'displayPostContent'    => [
				'type'    => 'boolean',
				'default' => true,
			],
			'postContentLength'     => [
				'type'    => 'number',
				'default' => 20,
			],
			'displayFeaturedImage'  => [
				'type'    => 'boolean',
				'default' => true,
			],
			'featuredImageSizeSlug' => [
				'type'    => 'string',
				'default' => 'large',
			],
			'displayCommentsCount'  => [
				'type'    => 'boolean',
				'default' => true,
			],
			'displayPostAuthor'     => [
				'type'    => 'boolean',
				'default' => false,
			],
		];

		$this->block_extra_attributes = [];
	}

	/**
	 * Add extra post meta such as comments count, author related info.
	 *
	 * @access public
	 *
	 * @filter rest_prepare_post
	 *
	 * @param WP_REST_Response $response Rest API response.
	 * @param WP_Post          $post     Post data.
	 * @param WP_REST_Request  $request  Rest API request data.
	 *
	 * @return WP_REST_Response
	 */
	public function add_extra_post_meta( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ) {
		$context = $request->get_param( 'context' );

		if ( 'edit' === $context ) {
			$response->data['authorDisplayName'] = get_the_author_meta( 'display_name', $post->post_author );
			$response->data['authorUrl']         = get_author_posts_url( $post->post_author, $response->data['authorDisplayName'] );
			$response->data['commentsCount']     = (int) get_comments_number( $post->id );
		}

		return $response;
	}

	/**
	 * Add comment_count to the list of permitted orderby values
	 *
	 * @access public
	 *
	 * @filter rest_post_collection_params
	 *
	 * @param array $params Rest API parameters.
	 *
	 * @return array
	 */
	public function add_comment_count_to_rest_orderby_params( $params ) {
		$params['orderby']['enum'][] = 'comment_count';
		return $params;
	}

	/**
	 * Registers a block on server.
	 *
	 * @access public
	 *
	 * @action init
	 */
	public function register_block() {
		register_block_type(
			$this->block_name,
			[
				'attributes'      => array_merge( $this->block_core_attributes, $this->block_extra_attributes ),
				'render_callback' => [ $this, 'render_block' ],
			]
		);
	}

	/**
	 * Renders the `material/recent-posts` block on server.
	 *
	 * @access public
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post content with latest posts added.
	 */
	public function render_block( $attributes ) {
		$class = 'wp-block-material-recent-posts';
		if ( isset( $attributes['align'] ) ) {
			$class .= ' align' . $attributes['align'];
		}
		$content = '<!-- No posts found -->';

		$args = [
			'posts_per_page'         => $attributes['postsToShow'],
			'post_status'            => 'publish',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts'    => true,
			'orderby'                => 'date',
			'order'                  => 'desc',
		];

		if ( ! empty( $attributes['category'] ) && 'material/recent-posts' === $this->block_name ) {
			$args['cat'] = absint( $attributes['category'] );
		}

		if ( 'material/hand-picked-posts' === $this->block_name ) {
			if ( empty( $attributes['posts'] ) ) {
				return sprintf(
					'<div class="%s">%s</div>',
					esc_attr( $class ),
					$content
				);
			}

			if ( ! empty( $attributes['orderby'] ) ) {
				if ( 'title' === $attributes['orderby'] ) {
					$args['orderby'] = 'title';
					$args['order']   = 'asc';
				} elseif ( 'popularity' === $attributes['orderby'] ) {
					$args['orderby'] = 'comment_count';
					$args['order']   = 'desc';
				}
			}

			$ids                    = array_map( 'absint', $attributes['posts'] );
			$args['post__in']       = $ids;
			$args['posts_per_page'] = count( $ids );
		}

		/**
		 * Filters the args for recent posts WP_Query.
		 *
		 * @param array $args       The args for the WP_Query.
		 * @param array $attributes The block's attributes.
		 */
		$args = apply_filters( 'mtb_recent_posts_query_args', $args, $attributes );

		$posts_query = new WP_Query( $args );

		// If we have posts and a valid layout style, load the template.
		if ( $posts_query->have_posts() && in_array( $attributes['style'], [ 'grid', 'list', 'masonry' ], true ) ) {
			ob_start();

			// Determine the template to show.
			$template = in_array( $attributes['style'], [ 'grid', 'list' ], true ) ? 'list-grid' : 'masonry';

			Template::get_template(
				"posts-{$template}.php",
				[
					'posts_query' => $posts_query,
					'attributes'  => $attributes,
				]
			);

			$content = ob_get_clean();
		}

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( $class ),
			$content
		);
	}
}