{% extends "main.tpl" %}

{% block title %}Add Account{% endblock %}

{% block body %}
<form method='post' action='/sadmin/Account/add2'>
<table>
<tr><td>Email</td><td><input type='text' name='email' value='{{ email }}'></td></tr>
<tr><td>HID</td><td><input type='text' name='hid' value='{{ hid }}'></td></tr>
<tr>
  <td>WebsiteDef</td>
  <td>
    <select name='websiteDef'>
    {% for i,def in websiteDefs %}
    	<option value='{{def}}'>{{def}}</option>
    {% endfor %}
    </select>
  </td>
</tr>
<tr><td colspan='2'><button type='submit'>Submit</button></td></tr>
</table>
</form>
{% endblock %}