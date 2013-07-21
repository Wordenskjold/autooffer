<?php

echo <h2>Login</h2>;
echo
	<ci:form action="/login">
		<input type="text" name="email" />
		<input type="password" name="password" />
		<input type="hidden" name="submitted" value="1" />
		<input type="hidden" name="return" value={$return} />
		<button type="submit">Login</button>
	</ci:form>;

echo <h2>Signup</h2>;
echo
	<ci:form action="/signup/create">
		<input type="text" name="firstName" placeholder="First name" />
		<input type="text" name="lastName" placeholder="Last name" />
		<input type="text" name="email" placeholder="Email" />
		<input type="password" name="password" placeholder="Password" />
		<button type="submit">Signup</button>
	</ci:form>;