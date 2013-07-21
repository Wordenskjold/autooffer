	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	</style>

<?php echo 
<ui:wrapper>
	<div id="container">
		<h1>Welcome to CodeIgniter <span class="name">{"{{name}}"}!</span></h1>

		<div id="body">
			<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

			<p>If you would like to edit this page you&quot;ll find it located at:</p>
			<code>application/views/welcome_message.php</code>

			<p>The corresponding controller for this page is found at:</p>
			<code>application/controllers/welcome.php</code>

			<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
		</div>
	</div>
</ui:wrapper>
?>

<?php
	print 
	<ci:form action="api/user">
		<input type="hidden" name="something" value="test" />
		<button class="btn btn-primary" type="submit">Create user</button>
	</ci:form>;
?>

<script>
	require(['models/Welcome', 'views/Welcome'], function(){
		var welcome = new views.Welcome({model: new models.Welcome()});
	});
</script>













