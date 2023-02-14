<div class="footer">
		<div class="container"><div class="col-md-4 footer-left">
				<a href="@url(':about')"><h6>About Us</h6></a>
				
			</div><div class="col-md-4 footer-middle">
			<h4>Twitter Feed</h4>
			<div class="mid-btm">
			<a class="twitter-timeline" data-width="300" data-height="250" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/ILAPI_Ghana?ref_src=twsrc%5Etfw">Tweets by ILAPI_Ghana</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
			</div>
        </div>
           <div class="col-md-4 footer-right">
				<h4>Quick Links</h4>
				<ul class="quick-links-ul">
                    <li><a href="{# url(':about') #}">About Us</a></li>
                    <li><a href="{# url(':events') #}">Upcoming Events</a></li>
                    <li><a href="{# url(':admin') #}">Admin</a></li>
					<li><a href="https://webmail.ilapi.org/" target="_blank">Web Mail</a></li>
					<li><a href="@url(':donate')">Donate</a></li>								
				</ul>
			</div>
			<div class="clearfix"></div>
	</div>
	
</div>
<!-- footer -->	<!-- footer-bottom -->

<div class="foot-nav">
	<div class="container">
		<ul>
            <li><a href={:str.homelink}>Home</a></li>			
            @each $header_links as $category => $sub:
                <li>
                    <a href="{# url(strtolower(':'.$category)) #}">{# $category #}</a>
                </li>            
			@endeach
			<div class="clearfix"></div>
		</ul>
	</div>
</div>
<!-- footer-bottom -->
<div class="copyright">
    <div class="container">
        <p class="copy_text"> &copy; Rasarp Multimedia Inc, Fly Corporation and <i>ilapi.org.v2.0.0&trade; </i>- @thisYear()</p>
    </div>
</div>