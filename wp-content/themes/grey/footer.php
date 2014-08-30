<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		</div><!-- #main -->
<footer class="footer">
  <!-- ScrollTop -->
  <a class="footer__scrolltop">Scroll</a>
  <!-- /ScrollTop -->

  <div class="footer__inner">
    <!-- Contact Info -->
    <ul class="footer__contact">
      <!-- General -->
      <li class="footer__contact__item">
        <h5 class="h5 u-text-white u-margin-bS u-bar-after">General</h5>
        <p class="p--address">
          <a href="tel:+1(212)518-4900" class="telephone">+1 (212) 518-4900</a>
          <a class="u-link-honey--coloured u-link-block" href="mailto:hello@hudsongray.co.uk">hello@hudsongray.co.uk</a>
          <a class="u-link-honey--coloured u-link-block" href="mailto:press@hudsongray.co.uk">press@hudsongray.co.uk</a>
          <a class="u-link-honey--coloured u-link-block" href="mailto:work@hudsongray.co.uk">work@hudsongray.co.uk</a>
        </p>

        <ul class="social__links">
          <li class="social__links__item">
            <a href="https://www.facebook.com/pages/hudsongray/127618770359" class="social__link social__link--facebook" target="_blank">Facebook</a>
          </li>

          <li class="social__links__item">
            <a href="http://www.linkedin.com/company/hudsongray-" class="social__link social__link--linkedin" target="_blank">LinkedIn</a>
          </li>

          <li class="social__links__item">
            <a href="https://twitter.com/hudsongray" class="social__link social__link--twitter" target="_blank">Twitter</a>
          </li>
        </ul>
      </li><!--

   --><li class="footer__contact__item" data-studio="london">
        <h5 class="h5 u-text-white u-margin-bS u-bar-after">London</h5>
        <p class="p--address">
          <span class="street-address">62 Shoreditch High Street</span><br />
          <span class="region">London E1 6JJ</span><br />
          <span class="country-name">United Kingdom</span><br />
          <a href="tel:+44(0)2076130433" class="telephone">+44 (0) 207 613 0433</a><br />
          <a target="_blank" class="u-link-mare--coloured u-link-block u-margin-tS" href="https://maps.google.com/maps?q=62+Shoreditch+High+Street+London+E1+6JJ+United+Kingdom&amp;hl=en&amp;sll=40.747244,-73.995747&amp;sspn=0.014972,0.023432&amp;gl=us&amp;hnear=62+Shoreditch+High+St,+London,+United+Kingdom&amp;t=m&amp;z=16">Go to Google Maps</a>
        </p>
      </li><!--

   --><li class="footer__contact__item"  data-studio="nyc">
        <h5 class="h5 u-text-white u-margin-bS u-bar-after">New York</h5>
        <p class="p--address">
          <span class="street-address">236W 27th Street. 12F</span><br />
          <span class="region">New York, NY. 10001</span><br />
          <span class="country-name">United States</span><br />
          <a href="tel:+1(212)518-4900" class="telephone">+1 (212) 518-4900</a><br />
          <a target="_blank" class="u-link-ohra--coloured u-link-block u-margin-tS" href="https://maps.google.com/maps?q=236W+27th+Street.+12F+New+York,+NY.+10001+United+States&amp;hnear=236+W+27th+St,+New+York,+10001&amp;gl=us&amp;t=m&amp;z=16">Go to Google Maps</a>
        </p>
      </li><!--

  --><li class="footer__contact__item" data-studio="malmo">
        <h5 class="h5 u-text-white u-margin-bS u-bar-after">Malmö</h5>
        <p target="_blank" class="p--address">
          <span class="street-address">Södra Förstadsgatan 2</span><br>
          <span class="region">211 43 Malmö</span><br />
          <span class="country-name">Sweden</span><br />
          <a href="tel:+46(0)40330480" class="telephone">+46 (0) 40 330 480</a><br />
          <a class="u-link-piglet--coloured u-link-block u-margin-tS" href="https://maps.google.com/maps?q=S%C3%B6dra+F%C3%B6rstadsgatan+2+211+43+Malm%C3%B6+Sweden&amp;hl=en&amp;ie=UTF8&amp;sll=51.524115,-0.077254&amp;sspn=0.012296,0.023432&amp;gl=us&amp;hnear=S%C3%B6dra+F%C3%B6rstadsgatan+2,+211+43+Malm%C3%B6,+Sweden&amp;t=m&amp;z=16">Go to Google Maps</a>
        </p>
      </li>
    </ul>
    <!-- /Contact Info -->

    <!-- Copyright -->
    <div class="footer__copyright">
      <h6 class='h6 u-text-white'>Copyright &copy; 2014 hudsongray studio Ltd. All rights reserved.</h6>
    </div>
    <!-- /Copyright -->
  </div>
</footer>
	<?php wp_footer(); ?>
    <!-- Include JS -->
<script src="/js/lib/jquery.min.js"></script>
<script src="/js/lib/jquery.cookie.js"></script>
<script src="/js/lib/jquery.fitText.js"></script>
<script src="/js/lib/swipe.js"></script>
<script src="/js/hudsongray-navigation.js"></script>
<script src="/js/hudsongray-geolocation.js"></script>
<script src="/js/hudsongray-blocks.js"></script>
<script src="/js/hudsongray-swipe.js"></script>
<!-- /Include Js -->

<script>
$(document).ready(function(){
	$(window).stellar();
});
</script>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-7940378-1']);
  _gaq.push(['_setDomainName', 'hudsongray.com']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
