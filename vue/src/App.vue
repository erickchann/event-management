<template>
	<div id="app">
		<router-view/>
	</div>
</template>

<style>

@import url('https://fonts.googleapis.com/css2?family=Architects+Daughter&display=swap');

:root {
	--border1: 255px 20px 225px 20px / 20px 225px 20px 255px;
	--border2: 255px 10px 200px 10px / 10px 200px 10px 255px;
}

* {
	box-sizing: border-box;
	font-family: 'Architects Daughter', cursive;
}

body {
	padding: 100px 0;
}

.container {
	width: 85%;
	margin: auto;
}

main {
	border: 3px solid black;
	border-radius: var(--border2);
	padding: 3rem;
}

header {
	display: flex;
	justify-content: space-between;
}

.login-btn, #register {
	height: fit-content;
	font-size: 1.2rem;
	padding: .5em 1.5em;
	background-color: white;
	border-radius: var(--border1);
	cursor: pointer;
	text-decoration: none;
	color: black;
	border: 2px solid black;
}

.event {
	border: 2px solid black;
	border-radius: var(--border1);
	padding: 2rem;
	display: block;
	color: black;
	text-decoration: none;
	margin-bottom: 3rem;
}

</style>