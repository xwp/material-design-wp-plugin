<?php
/**
 * Base class to allow sharing functionality between blocks listing posts
 * such as Recent Posts and Hand-picked posts blocks.
 *
 * @package MaterialThemeBuilder
 */

namespace MaterialThemeBuilder\Blocks;

use MaterialThemeBuilder\Plugin;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Posts_List_Blocks_Base class.
 */
class Posts_List_Blocks_Base {
	/**
	 * Plugin class.
	 *
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * @var string
	 */
	public $block_name;

	/**
	 * @var array
	 */
	public $block_core_attributes;

	/**
	 * @var array
	 */
	public $block_attributes;

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin                = $plugin;
		$this->block_core_attributes = [
			'className'             => [
				'type' => 'string',
			],
			'style'                 => [
				'type'    => 'string',
				'default' => 'masonry',
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
				'default' => 10,
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

		$this->block_attributes = [];
	}

	/**
	 * Initiate the class.
	 *
	 * @access public
	 */
	public function init() {
		$this->plugin->add_doc_hooks( $this );
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
			$response->data['authorDisplayName'] = get_the_author_meta( 'display_name', $post->author );
			$response->data['authorUrl']         = get_author_posts_url( $post->author, $response->data['authorDisplayName'] );
			$response->data['commentsCount']     = (int) get_comments_number( $post->id );
		}

		return $response;
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
				'attributes'      => array_merge( $this->block_core_attributes, $this->block_attributes ),
				'render_callback' => [ $this, 'render_block' ],
			]
		);
	}

	/**
	 * Renders the block on server.
	 *
	 * @access public
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns the post content with latest posts added.
	 */
	public function render_block( $attributes ) {
		return sprintf(
			'hello world' // todo: Do the rendering.
		);
	}

}