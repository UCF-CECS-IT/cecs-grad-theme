<?php
/*
 Template Name: Research Positions Index
 */

get_header();
the_post();

$page = $_GET['page_number'] ?? 1;

$args = [
    'post_type'=> 'research_position',
    'order'    => 'DESC',
	'posts_per_page' => 20,
    'paged' => $page
];

// check for stick post(s)
$stickyArgs = array(
	'post_type'=> 'research_position',
	'order'    => 'ASC',
	'meta_query' => array(
		array(
			'key' => 'research_sticky',
			'value' => '1',
			'compare' => '=',
		)
	)
);

/**
 * For each filter, add the same query to the stickyArgs - this will remove
 * sticky results when they do not match filter criteria, but leave them in
 * the top position when they do.
 */
if ( $_GET['department'] ?? false ) {
	$departmentFilter = array(
        'key' => 'research_department',
        'value' => $_GET['department'],
        'compare' => '=',
    );

    $args['meta_query'][] = $departmentFilter;
	$stickyArgs['meta_query'][] = $departmentFilter;
}

if ( $_GET['degree_programs'] ?? false ) {
    $programFilter = array(
        'key' => 'research_degree_programs',
        'value' => $_GET['degree_programs'],
        'compare' => 'like',
    );

	$args['meta_query'][] = $programFilter;
	$stickyArgs['meta_query'][] = $programFilter;
}

if ( $_GET['keyword'] ?? false ) {
    $keywordFilter = array(
        'key' => 'research_keywords',
        'value' => $_GET['keyword'],
        'compare' => 'like',
    );

	$args['meta_query'][] = $keywordFilter;
	$stickyArgs['meta_query'][] = $keywordFilter;
}

$stickyQuery = new WP_Query( $stickyArgs );

$additionalParamenters = '';

if ($_GET['department'] ?? false) {
    $additionalParamenters .= 'department=' . urlencode( $_GET['department'] ) . '&';
}

if ($_GET['degree_programs'] ?? false) {
    $additionalParamenters .= 'degree_programs=' . urlencode( $_GET['degree_programs'] ) . '&';
}

if ($_GET['keyword'] ?? false) {
    $additionalParamenters .= 'keyword=' . urlencode( $_GET['keyword'] ) . '&';
}

$query = new WP_Query( $args );
$posts = $query->posts;

// Prepend the sticky post if on page 1 and has no filters
if ( $page == 1  ) {
	$posts = array_merge( $stickyQuery->posts, $posts );
	$posts = array_unique( $posts, SORT_REGULAR );

// Remove the sticky post if on subsequent pages to avoid duplication
} else {
	foreach( $stickyQuery->posts as $stickyPost ) {
		$duplicate = array_search( $stickyPost, $posts );
		unset( $posts[$duplicate] );
	}
}

?>

<div class="container">
	<?php the_content(); ?>
</div>

<div class="container-full px-5 mt-4 mt-sm-5 mb-2 pb-sm-4">
    <!-- Search Bar -->
    <div class="row">
        <div class="col-xl-1 col-lg-2 offset-xl-1 ">
            <h5 class="heading-underline mb-0">Filter</h5>
        </div>
    </div>

	<form method="GET" action="">
        <div class="form-group row justify-content-center mt-2 mb-4 align-items-center">
			<label for="search_key" class="col-xl-1 col-lg-2 col-form-label text-xl-right mt-2 pr-0 smaller">Keyword:</label>
            <div class="col-xl-2 col-lg-4 mt-2">
                <input class="form-control form-control-sm" type="text" name="keyword" <?php if( ($_GET['keyword'] ?? null) ) echo 'value="' . $_GET['keyword'] . '"'; ?>>
            </div>
            <label for="search_key" class="col-xl-1 col-lg-2 col-form-label text-xl-right mt-2 pr-0 smaller">Department:</label>
            <div class="col-xl-2 col-lg-4 mt-2">
                <select id="search_key" class="form-control form-control-sm" name="department">
                    <option></option>
					<option <?php if( ($_GET['department'] ?? null) == 'CECE') echo 'selected'; ?> value="CECE">CECE</option>
                    <option <?php if( ($_GET['department'] ?? null) == 'CS') echo 'selected'; ?> value="CS">CS</option>
                    <option <?php if( ($_GET['department'] ?? null) == 'ECE') echo 'selected'; ?> value="ECE">ECE</option>
                    <option <?php if( ($_GET['department'] ?? null) == 'IEMS') echo 'selected'; ?> value="IEMS">IEMS</option>
                    <option <?php if( ($_GET['department'] ?? null) == 'MAE') echo 'selected'; ?> value="MAE">MAE</option>
                    <option <?php if( ($_GET['department'] ?? null) == 'MSE') echo 'selected'; ?> value="MSE">MSE</option>
                </select>
            </div>

			<label for="search_key" class="col-xl-1 col-lg-2 col-form-label text-xl-right mt-2 pr-0 smaller">Level:</label>
            <div class="col-xl-2 col-lg-4 mt-2">
                <select id="search_key" class="form-control form-control-sm" name="degree_programs">
                    <option></option>
					<option <?php if( ($_GET['degree_programs'] ?? null) == 'Postdoc') echo 'selected'; ?> value="Postdoc">Postdoc</option>
					<option <?php if( ($_GET['degree_programs'] ?? null) == 'PhD') echo 'selected'; ?> value="PhD">Doctoral (PhD)</option>
                    <option <?php if( ($_GET['degree_programs'] ?? null) == 'MS') echo 'selected'; ?> value="MS">Master's (MS)</option>
					<option <?php if( ($_GET['degree_programs'] ?? null) == 'Honors') echo 'selected'; ?> value="Honors">Honors College/Honors-in-the-Major</option>
                    <option <?php if( ($_GET['degree_programs'] ?? null) == 'Undergrad') echo 'selected'; ?> value="Undergrad">Undergrad</option>

                </select>
            </div>


            <div class="col-xl-2 col-lg-4 mt-2">
				<a class="btn btn-default btn-sm" href="<?php echo get_permalink(); ?>">Reset</a>
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </div>
        </div>
    </form>
</div>

<div class="container-full">
	<!-- Results -->
	<div class="row mx-0">

		<?php foreach ( $posts as $post ): ?>

			<div class="col-md-6 col-lg-4 mb-4">
				<div class="card h-100 border-0 box-shadow-soft d-flex flex-column justify-content-start">
					<?
						$video = get_field( 'research_video_url', $post->ID );
						$poster = get_field( 'research_video_image', $post->ID );
						$youtubeLink = strstr($video, 'youtube');
					?>
					<?php if ( $video && ( $video != $poster) ): ?>
						<?php if ( $poster ): ?>
							<!-- Poster -->
							<div class="embed-poster">
								<div class="embed-play"></div>
								<img class="embed-img w-100 img-fluid" src="<?php echo $poster; ?>">
							</div>
						<?php endif; ?>
						<div class="embed-responsive embed-responsive-16by9 <? if ($poster) echo 'd-none'; ?>">
							<?php if ( $youtubeLink ): ?>
								<iframe
									class="embed-responsive-item"
									src="<?php echo $video; ?>"
									controls allowfullscreen
									>
								</iframe>
							<?php else: ?>
								<video src="<?php echo $video; ?>" controls allowfullscreen></video>
							<?php endif; ?>
                        </div>
					<?php else: ?>
						<?php if ( $poster ): ?>
							<img class="card-img-top" src="<?php echo $poster; ?>">
						<?php endif; ?>
					<?php endif; ?>
					<div class="card-block f-flex flex-column align-items-start">
						<h5 class="mb-1">
							<?php echo get_field( 'research_title', $post->id ); ?>
							<br>
							<small class="text-muted">
								<b>Position Type:</b>
								<?php foreach ( get_field( 'research_position_types', $post->ID ) as $index => $type ): ?>
									<?php if ( count( get_field( 'research_position_types', $post->ID ) ) == 1 || $index == ( count( get_field( 'research_position_types', $post->ID ) ) - 1) ): ?>
										<?php echo $type; ?>&nbsp;
									<?php else: ?>
										<?php echo $type; ?>,&nbsp;
									<?php endif; ?>
								<?php endforeach; ?>
							</small>
						</h5>
						<hr class="bg-primary w-100 mt-1 mb-0">
						<table class="table mb-0 font-size-sm">
							<tr>
								<th>Department:</th>
								<td><?php echo get_field( 'research_department', $post->ID ); ?></td>
							</tr>
							<tr>
								<th>Description:</th>
								<td><?php echo get_field( 'research_position_description', $post->ID ); ?></td>
							</tr>
							<tr>
								<th>Degree Programs:</th>
								<td>
									<?php foreach ( get_field( 'research_degree_programs', $post->ID ) as $index => $type ): ?>
										<?php if ( count( get_field( 'research_degree_programs', $post->ID ) ) == 1 || $index == ( count( get_field( 'research_degree_programs', $post->ID ) ) - 1) ): ?>
											<?php echo $type; ?>&nbsp;
										<?php else: ?>
											<?php echo $type; ?>,&nbsp;
										<?php endif; ?>
									<?php endforeach; ?>
								</td>
							</tr>
							<tr>
								<th>Advisor:</th>
								<td><a href="mailto:<?php echo get_field( 'research_advisor_email', $post->ID ); ?>"><?php echo get_field( 'research_advisor_name', $post->ID ); ?></a></td>
							</tr>
							<tr>
								<th>Lab/Group Name:</th>
								<td>
									<?php if ( get_field( 'research_lab_webpage', $post->ID ) ): ?>
										<a href="<?php echo get_field( 'research_lab_webpage', $post->ID ); ?>" target="_blank">
									<?php endif; ?>

									<?php echo get_field( 'research_lab', $post->ID ); ?>

									<?php if ( get_field( 'research_lab_webpage', $post->ID ) ): ?>
										<a href="<?php echo get_field( 'research_lab_webpage', $post->ID ); ?>">
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th>Keywords:</th>
								<td><?php echo get_field( 'research_keywords', $post->ID ); ?></td>
							</tr>
							<?php if ( get_field( 'research_locations', $post->ID ) ): ?>
								<tr>
									<th>Location(s):</th>
									<td>
									<?php foreach ( get_field( 'research_locations', $post->ID ) as $index => $type ): ?>
										<?php if ( count( get_field( 'research_locations', $post->ID ) ) == 1 || $index == ( count( get_field( 'research_locations', $post->ID ) ) - 1) ): ?>
											<?php echo $type; ?>&nbsp;
										<?php else: ?>
											<?php echo $type; ?>,&nbsp;
										<?php endif; ?>
									<?php endforeach; ?>
									</td>
								</tr>
							<?php endif; ?>
						</table>
					</div>
				</div>
			</div>

		<?php endforeach; ?>

	</div>

	<!-- Pagination links -->
	<nav class="justify-content-center py-4">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="?<?php echo $additionalParamenters; ?>page_number=1">First</a></li>

            <?php for ($i=1; $i < $query->max_num_pages + 1; $i++): ?>
                <li class="page-item"><a class="page-link" href="?<?php echo $additionalParamenters; ?>page_number=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>

            <li class="page-item"><a class="page-link" href="?<?php echo $additionalParamenters; ?>page_number=<?php echo $query->max_num_pages; ?>">Last</a></li>
        </ul>
    </nav>
</div>

<?php get_footer(); ?>
