{% extends "main.tpl" %}

{% block title %}Edit Account{% endblock %}

{% block body %}
<form method='post' action='/sadmin/Account/edit2?id={{account.getId()}}'>
<table>
<tr><td>Email</td><td><input type='text' name='email' value='{{account.getEmail()}}'></td></tr>
<tr><td>Password</td><td><input type='text' name='password' value='{{account.getPassword()}}'></td></tr>
<tr><td></td><td><button type='submit'>Save Account</button></td></tr>
</table>
</form>

<h2>Websites</h2>
<ul>
{% for website in account.getWebsites() %}
<li><a href='/sadmin/Website/edit?id={{website.getId()}}'>{{website.getDefaultHost().getHostname()}}</a></li>
{% endfor %}
</ul>

{% endblock %}