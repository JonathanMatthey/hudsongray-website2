<?
/*
Template Name: Careers
*/
?>

<? $ustwo = new Ustwo(); ?>

<? get_header(); ?>

<!--
<h2 class="grid_12"><span></span></h2>
-->

	<div id="join-us">
	  <div class="block block__table">
	    <div class="block__table__split u-bg-honey u-text-white">
	      <div class="block__inner block__inner--split">
	        <h1 class="h1 block__title u-text-nonBlack">
	          <span class="">Talent</span><br/>and<br/><span class="">Action</span>
	        </h1>
	        <p class="u-margin-bN u-text-nonBlack">Are you a dreamer and a doer with a desire to do the best work of your life? If so, we’d love to hear from you. Here’s our latest openings.</p>
	      </div>
	    </div>
	    <div class="block__table__split block--heroish" style="background-image: url('http://ustwo.com//images/join-us/join-us-hero.jpg');"></div>
	  </div>
	
     <div class="studios__item london" data-studio="london">
       <!-- Studio Details -->
       <div class="studios__item__details u-bg-mare u-text-white">
         <h3>London</h3>
       </div>

       <ul class="jobs">

		<?php
			$locationId = 4;
			include (TEMPLATEPATH . '/career-box.php');
		?>
       </ul>
       <!-- /Studio Details -->
     </div><!--

  --><div class="studios__item new-york" data-studio="nyc">
       <!-- Studio Details -->
       <div class="studios__item__details u-bg-ohra u-text-white">
         <h3>New York</h3>
       </div>
	       <ul class="jobs">
			<?php
				$locationId = 55;
				include (TEMPLATEPATH . '/career-box.php');
			?>
	       </ul>
       <!-- /Studio Details -->
     </div><!--

  --><div class="studios__item" data-studio="malmo">
       <!-- Studio Details -->
       <div class="studios__item__details u-bg-piglet u-text-white">
         <h3>Malmö</h3>
       </div>

       <ul class="jobs malmo">
		<?php
			$locationId = 5;
			include (TEMPLATEPATH . '/career-box.php');
		?>
	   </ul>
       <!-- /Studio Details -->
     </div>
   </div>


<? get_footer(); ?>