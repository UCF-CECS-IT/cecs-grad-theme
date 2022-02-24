<?php
/*
 Template Name: Grad Defense Home
 */
get_header();

$gdconnection = grad_defense_connection();

if (!$gdconnection) {
    die('Could not connect: ' . mysqli_error($gdconnection));
}

$year = grad_defense_year($post->name);
$startDate = grad_defense_start_date($year);
$endDate = grad_defense_end_date($year);
$res = get_grad_defenses($gdconnection, $startDate, $endDate);
$submissionArray = grad_defenses_build_array($res);

$title = $year ? "$year Defenses" : 'Upcoming Defenses';

?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="container my-3">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <h2 class="mt-3"><?php echo $title; ?></h2>

                <p class="lead"><a href="https://grad.cecs.ucf.edu/college-of-engineering-and-computer-science-policy-on-dissertation-thesis-defenses/">College Policy on Dissertation/Thesis Defenses</a></p>

				<?php if ( $year ): ?>
					<p><a href="/grad-defenses/">Back to Upcoming Defenses</a></p>
				<?php endif; ?>

				<?php the_content(); ?>

                <?php foreach($submissionArray as $defenseDate => $presentations): ?>
                    <div class="card mt-3 mb-4">
                        <h5 class="card-header bg-primary-lighter"><?php echo $defenseDate; ?></h5>
                        <ul class="list-group list-group-flush">
                            <?php foreach($presentations as $presentation): ?>
                                <li class='list-group-item font-size-sm'><a class='nobold' href="https://www.cecs.ucf.edu/graddefense-old/pdf/<?php echo $presentation['ID'];?>"><?php echo $presentation['department']; ?> Defense - <?php echo $presentation['fname']; ?> <?php echo $presentation['lname']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="col-lg-3 col-md-12">
                <?php get_template_part('parts/sidebar', 'events'); ?>
            </div>
        </div>
    </div>
<?php endwhile; endif; ?>



<?php get_footer();
