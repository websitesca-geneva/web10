{% extends "main.tpl" %}

{% block title %}Edit Website{% endblock %}

{% block body %}
<h3>Website: <a href='/sadmin/Website/edit?id={{ website.getId() }}'>{{ website.getDefaultHost().getHostname() }}</a></h3>
<h3>Account: <a href='/sadmin/Account/edit?id={{ website.getAccount().getId() }}'>{{ website.getAccount().getEmail() }}</a></h3>

<h2>Website Properties</h2>
<form method='post' action='/sadmin/Website/edit2?id={{website.getId()}}'>
<table>
<tr>
  <td>Website Def</td>
  <td>
    <select name='websiteDef'>
    {% for i,def in websiteDefs %}
    	{% if website.websiteDef == def %}
    		<option value='{{def}}' selected>{{def}}</option>
    	{% else %}
    		<option value='{{def}}'>{{def}}</option>
    	{% endif %}
    {% endfor %}
    </select>
  </td>
</tr>
<tr><td colspan='2'><button type='submit'>Save Website</button></td></tr>
</table>
</form>

<h2>Hosts</h2>
<form method='post' action='/sadmin/Website/addHost?id={{website.getId()}}'>
<input type='text' name='hostname'> <button type='submit'>Add Host</button>
</form>
<ul>
{% for host in website.getHosts() %}
<li>
  {% if (website.getDefaultHost().getId() == host.getId()) %}
    <span class='default-host'>{{ host.getHostname() }}</span>
  {% else %}
    <span>{{ host.getHostname() }}</span>
  {% endif %}
  <a href='/sadmin/Website/deleteHost?website_id={{website.getId()}}&host_id={{host.getId()}}' onclick="javascript: return confirm('Are you sure?');">Delete</a>
  <a href='/sadmin/Website/setDefaultHost?website_id={{website.getId()}}&host_id={{host.getId()}}'>Default</a>
</li>
{% endfor %}
</ul>

{% endblock %}