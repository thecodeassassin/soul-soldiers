<h1 style="text-align: right">Inschrijving {{ event.name }}</h1>

<p class="lead">Beste {{ user.realName }},</p>

<p>
    Bedankt voor je inschrijving voor {{ event.name }}!
    <br /><br />
    Om je plaats op {{ event.name }} zeker te stellen, dien je een entreeticket aan te schaffen.<br/><br />

    Klik <a href="{{ url('event/pay/' ~  event.systemName) }}">hier</a> om je entreeticket aan te schaffen.
    <br />
    <br />

    Bedankt en tot snel!

</p>
