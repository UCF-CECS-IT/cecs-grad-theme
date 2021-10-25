<?php

/**
 * Adds custom
 *
 * @since 0.1.8
 * @param array $layouts
 * @return array
 */
function add_thesis_rss_layouts( $layouts ) {
    $layouts['thesis'] = 'Dissertation and Thesis layout';
    return $layouts;
}

add_filter( 'ucf_rss_get_layouts', 'add_thesis_rss_layouts', 10, 1 );

/**
 * Adds Thesis layout wrapper
 *
 * @since 0.1.8
 * @param string $content
 * @param array $items
 * @param array $args
 * @return string
 */
function display_thesis_before( $content, $items, $args ) {
    return '<div class="ucf-rss-feed ucf-rss-feed-thumbnail ucf-rss-feed-thesis">';
}

add_filter( 'ucf_rss_display_thesis_before', 'display_thesis_before', 10, 3 );

/**
 * Adds Thesis layout title
 *
 * @since 0.1.8
 * @param string $content
 * @param array $items
 * @param array $args
 * @return string
 */
function display_thesis_title( $content, $items, $args ) {
    $formatted_title = '';

    if ( $args['list_title'] ) {
        $formatted_title = '<h2 class="ucf-rss-title">' . $args['list_title'] . '</h2>';
    }

    return $formatted_title;
}

add_filter( 'ucf_rss_display_thesis_title', 'display_thesis_title', 10, 3 );

/**
 * Adds looped individual item content
 *
 * @since 0.1.8
 * @param string $content
 * @param array $items
 * @param array $args
 * @return string
 */
function display_thesis( $content, $items, $args ) {
    if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
    ob_start();

	// Build custom feed array. Needed to capture downloads tag
	$feed = [];

	foreach($items as $item) {
		$title = $item->sanitize( $item->get_title(), SIMPLEPIE_CONSTRUCT_TEXT );
		$link = $item->sanitize( $item->get_link(), SIMPLEPIE_CONSTRUCT_TEXT );
		$author = $item->sanitize( $item->get_item_tags( SIMPLEPIE_NAMESPACE_RSS_20, 'author' )[0]['data'], SIMPLEPIE_CONSTRUCT_TEXT );
		$downloads = $item->get_item_tags( SIMPLEPIE_NAMESPACE_RSS_20, 'downloads' )[0]['data'];

		// trim longer descriptions
		$description = $item->sanitize( $item->get_description(), SIMPLEPIE_CONSTRUCT_HTML );
		$description = substr( $description, 0, 400 ) . '...';

		$feed[] = array(
			'title' => $title,
			'link' => $link,
			'author' => $author,
			'description' => $description,
			'downloads' => $downloads
		);
	}

	// Sorted largest to smallest
	usort($feed, function($a, $b) {
		return -( (int) $a['downloads'] <=> (int) $b['downloads'] );
	});

	?>
	<ul class="ucf-rss-items">
		<?php for ($i=0; $i < count( $feed ); $i++):
			$entry = $feed[$i];
			?>
			<li class="ucf-rss-item <?php if ( $i > 4 ) echo 'd-none'; ?>">
				<a class="ucf-rss-thesis-link text-secondary" href="<?php echo $entry['link']; ?>" target="_blank">
					<div class="d-flex flex-row justify-content-between">
						<h6 class="ucf-rss-thesis-title">
							<?php echo $entry['title']; ?>
						</h6>
						<span class="small">
							<b>Downloads:</b> <?php echo $entry['downloads']; ?>
						</span>
					</div>
					<div class="text-muted font-size-sm">
						<?php echo $entry['author']; ?>
					</div>
					<div class="font-size-sm"><?php echo $entry['description']; ?></div>
				</a>
			</li>
		<?php endfor; ?>
	</ul>
	<?php if ( count($feed) > 5 ): ?>
		<div class="text-center">
			<button class="btn btn-sm btn-primary ucf-rss-thesis-reveal">Show More</button>
		</div>
	<?php endif;?>
	<?php


    return ob_get_clean();
}

add_filter( 'ucf_rss_display_thesis', 'display_thesis', 10, 3 );

/**
 * Closes Thesis layout wrapper
 *
 * @since 0.1.8
 * @param string $content
 * @param array $items
 * @param array $args
 * @return string
 */
function display_thesis_after( $content, $items, $args ) {
    return '</div>';
}

add_filter( 'ucf_rss_display_thesis_after', 'display_thesis_after', 10, 3 );
