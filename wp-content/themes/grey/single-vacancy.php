<?
// Configure the header
$noBanner = true;
$noBlogInfo = true;

$jobsEmail = 'joinus@hudsongray.co.uk'
?>

<? include (TEMPLATEPATH . '/header.php'); ?>

<div class="entry-content vacancy">



<? while (have_posts()): the_post(); ?>

<?
	$row = array();

	$vacanciesMeta = array();
	$vacanciesMeta['job_title']		= array( 'Job Title', 'input' );
	$vacanciesMeta['description']	= array( 'Description', 'tinymce' );
	$vacanciesMeta['excerpt']		= array( 'Excerpt', 'textarea' );
	$vacanciesMeta['job_level']		= array( 'Level', 'input' );
	$vacanciesMeta['job_salary']	= array( 'Salary', 'input' );
	$vacanciesMeta['job_benefits']	= array( 'Benefits', 'input' );
	$vacanciesMeta['job_holidays']	= array( 'Holidays', 'input' );
	$vacanciesMeta['job_closing_date'] = array( 'Closing Date For Applications', 'input' );
	$vacanciesMeta['experience']	= array( 'Experience &amp; Skills', 'tinymce' );
	$vacanciesMeta['project']	= array( 'Project &amp; Team Management', 'tinymce' );
	$vacanciesMeta['tools']	= array( 'The tools we use', 'tinymce' );
	$vacanciesMeta['character']	= array( 'Character requirements', 'tinymce' );
	$vacanciesMeta['qualifications']	= array( 'Qualifications', 'tinymce' );

	foreach ( $vacanciesMeta as $id => $properties )
	{
		$value = get_post_custom_values( "vacancy_$id", get_the_ID() );
		$row[$id] = $value[0];
	}

	$location = wp_get_post_terms(get_the_ID(), "vacancy_location");
	$row['location'] = $location[0]->name;

	$title = get_the_title()." - ".$row['location'];
?>
	<div class="grid_12 content-row">

		<div class="grid_12 titlebar">
			<h2><? echo $title; ?></h2>
		</div>

	<div class="grid_8 role">
	<p>
		<? echo $row['description']; ?>
	</p>
	</div>

	<div class="grid_4">
		<dl class="info">
			<? if (strlen($row['job_title'])>0) { ?>
			  	<dt>Job Title:</dt>
			    <dd><? echo $row['job_title']; ?></dd>
			<? } ?>
			<? if (strlen($row['job_level'])>0) { ?>
			  	<dt>Level:</dt>
			    <dd><? echo $row['job_level']; ?></dd>
			<? } ?>
			<? if (strlen($row['location'])>0) { ?>
<!-- 				<dt>Location:</dt> -->
<!-- 				<dd><? echo $row['location']; ?></dd> -->
			<? } ?>
			<? if (strlen($row['job_salary'])>0){ ?>
				<dt>Salary:</dt>
				<dd><? echo $row['job_salary']; ?></dd>
			<? } ?>
			<? if (strlen($row['job_benefits'])>0) { ?>
				<dt>Benefits:</dt>
				<dd><? echo $row['job_benefits']; ?></dd>
			<? } ?>
			<? if (strlen($row['job_holidays'])>0) { ?>
				<dt>Holidays:</dt>
				<dd><? echo $row['job_holidays']; ?></dd>
			<? } ?>
			<? if (strlen($row['job_closing_date'])>0) { ?>
				<dt>Closing Date For Applications:</dt>
				<dd><? echo $row['job_closing_date']; ?></dd>
			<? } ?>
		</dl>
	</div>
	</div>

	<div class="grid_12 content-row details">

	<? if ( strlen( trim( $row['experience'] ) ) ): ?>
	<div class="grid_4">
			<h3>Skills</h3>
			<? echo $row['experience']; ?>
	</div>
	<? endif; ?>

	<? if ( strlen( trim( $row['project'] ) ) || strlen( trim( $row['tools'] ) ) ): ?>
	<div class="grid_4">
		<? if ( strlen( trim( $row['project'] ) ) ): ?>
			<h3>Responsibilities</h3>
			<? echo $row['project']; ?>
		<? endif; ?>

		<? if ( strlen( trim( $row['tools'] ) ) ): ?>
			<h5>The tools we use</h5>
			<? echo $row['tools']; ?>
		<? endif; ?>
	</div>
	<? endif; ?>

	<? if ( strlen( trim( $row['character'] ) ) || strlen( trim( $row['qualifications'] ) ) ): ?>
	<div class="grid_4">
		<? if ( strlen( trim( $row['character'] ) ) ): ?>
			<h5>Character Requirements</h5>
			<? echo $row['character']; ?>
		<? endif; ?>

		<? if ( strlen( trim( $row['qualifications'] ) ) ): ?>
			<h5>Qualifications</h5>
			<? echo $row['qualifications']; ?>
		<? endif; ?>
	</div>
	<? endif; ?>

	</div>
	<?
	switch ($location[0]->term_id) {
		case 4:
			$jobsEmail = "joinus.ldn@hudsongray.co.uk";
			break;
		case 5:
			$jobsEmail = "joinus.mal@hudsongray.se";
			break;
		case 55:
			$jobsEmail = "joinus.nyc@hudsongray.us";
			break;
	}
	?>
	<a href="mailto:<? echo $jobsEmail; ?>?subject=<? echo $title; ?>" class="apply btn">Apply</a>


<? endwhile; ?>
</div>

	<?php //twentythirteen_post_nav(); ?>
<? get_footer(); ?>
