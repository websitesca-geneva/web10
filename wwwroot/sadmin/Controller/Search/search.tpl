<h1>Search Results</h1>

<ul id='searchResults'>

{% for account in accounts %}

<li>
  <a href='/sadmin/Account/edit?id={{account.getId()}}'>{{ account.getEmail() }}</a>
  {% if (account.getWebsites()) %}
  <ul>
  {% for website in account.getWebsites() %}
    <li><a href='/sadmin/Website/edit?id={{website.getId()}}'>{{ website.getDefaultHost().getHostname() }}</a> (def:{{ website.getWebsiteDef() }})</li>
  {% endfor %}
  </ul>
  {% endif %}
</li>


{% else %}

<li>No accounts found</li>

{% endfor %}

</ul>