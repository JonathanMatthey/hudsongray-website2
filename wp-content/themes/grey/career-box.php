<? foreach( $ustwo->vacancies( $locationId ) as $vacancy ) : ?>
	<li class="jobs__item">
		<a href="<? echo $vacancy['permalink']; ?>" class="jobs__item__link">
		 <h4 class="u-text-mare u-bar-after--mare"><? echo $vacancy['title']; ?></h4>
		 <p class="p--small"><? echo $vacancy['excerpt']; ?></p>
		</a>
	</li>
<? endforeach; ?>
<? if (count($ustwo->vacancies( $locationId )) == 0){ ?>
	<li class="jobs__item">
		<div href="#" class="jobs__item__link">
		 <h4 class="u-text-mare u-bar-after--mare">Currently no openings</h4>
		 <p class="p--small">We've no openings right now, but we're always interested in hearing from people with strong skills, impressive minds and unstoppable passion - so say hi to <a href="mailto:joinus@ustwo.co.uk">joinus@ustwo.co.uk</a></p>
		</div>
	</li>
<? } ?>