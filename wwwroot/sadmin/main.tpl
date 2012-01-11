<html>
<head>
<title>{{ title }}</title>
<link rel='stylesheet' href='/sadmin/main.css' />
<script type='text/javascript' src='/js/jquery.js'></script>
<script type='text/javascript' src='/sadmin/js/main.js'></script>
</head>
<body>

<div id='header'><a href='/sadmin'>websites.ca superadmin</a></div>

<div id='menu'>
<input type='text' name='search' value='<search>' id='search'>
</div>

{% if error != null %}
<div id='error'>{{ error }}</div>
{% endif %}
{% if message != null %}
<div id='message'>{{ message }}</div>
{% endif %}

<div id='body'>
<h1>{{title}}</h1>

{% block body %}{% endblock %}
</div>

</body>
</html>