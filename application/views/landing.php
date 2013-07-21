<?php echo 
	<header>
		<img class="hero" src="/static/gfx/hero1.jpg" />
		<ui:wrapper>
			<div class="center">
				<div class="logo"><span>Auto</span>Offer</div>
			</div>
			<h1>{"{{h1}}"}</h1>
			<hr />
			<p class="intro">{"{{intro}}"}</p>
		</ui:wrapper>
	</header>;
	echo
	<ui:wrapper>
		<ci:form action="/api/landing/subscribe">
  			<input name="email" type="text" placeholder="Email" />
  			<input name="make_model" type="text" placeholder={"{{make_model}}"} />
		</ci:form>
		<div class="center"><button>{"{{sign_me_up}}"}</button></div>
	</ui:wrapper>;
	echo <div class="clearfix"></div>;
	echo
	<ui:wrapper>
		<h2>{"{{h2}}"}</h2>
		<section class="points">
			<div class="columns large-8">
				<h3>{"{{points_h3_1}}"}</h3>
				<i class="icon-info"></i>
				<p class="details">{"{{points_details_1}}"}</p>
			</div>
			<div class="columns large-8">
				<h3>{"{{points_h3_2}}"}</h3>
				<i class="icon-cloud-upload"></i>
				<p class="details">{"{{points_details_2}}"}</p>
			</div>
			<div class="columns large-8">
				<h3>{"{{points_h3_3}}"}</h3>
				<i class="icon-time"></i>
				<p class="details">{"{{points_details_3}}"}</p>
			</div>
			<div class="clearfix"></div>
		</section>
	</ui:wrapper>;
?>

<script>
	require(['views/LandingPage', 'models/PreLaunchSubscriber'], function(){
		new views.LandingPage({
			el: '.landing',
			model: new models.PreLaunchSubscriber()
		});
	});
</script>