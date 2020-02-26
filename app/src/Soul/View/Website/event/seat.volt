{% set seatImagePosX = seatMap.posX %}
{% set seatImagePosY = seatMap.posY %}
{% set crewSize = event.crewSize %}
{% set tableSize = 2 %}

<h1>Plek reserveren</h1>
{% if not seatingAvailable %}
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            {% if userSeat == 0 %}
                Helaas, u kunt geen plek meer reserveren voor dit evenement.
            {% else %}
                Het is niet meer mogelijk om van plek te wisselen.
            {% endif %}
        </div>
    </div>
</div>
{% endif %}
<div class="row">

    <div class="col-md-12">

        <div class="seating-table {{ seatMap.cssClass|default('') }}">
            <div class="seatmap-wrapper" style="background-image: url({{ seatMap.url }})">

                {% set seatNum = 1 %}
                <div class="seatmap" style="position: absolute; left: {{ seatImagePosX }}px; top: {{ seatImagePosY }}px">

                    <div class="seat-row-wrapper" style="width: {{ blockSizePx }}px">

                        {% for row in map %}

                            <div class="seat-table" style="width:{{ blockSizePx }}px">

                                <div class="seat-row">

                                    {% for seatName in row %}
                                    
                                        {% set seatBlocked = (seatName == "") %}
                                        
                                        <div class="seat {% if seatBlocked %} blocked {% else %} {% if seatName in takenSeats %}taken{% elseif seatName === userSeat %}yours{% else %}free{% endif %} {% endif %}">
                                            
                                            {% if not seatBlocked %}
                                                {% if seatName not in takenSeats and seatName !== userSeat and seatingAvailable %}
                                                    <a href="{{ url('event/'~ event.systemName ~ '/reserve-seat/' ~ seatName ) }}"
                                                       data-toggle='tooltip'
                                                       title="Deze plek is vrij"
                                                       onclick="return reserveSeat()">
                                                        {{ seatName }}
                                                    </a>
                                                {% else %}
                                                    <span title="{% if seatName === userSeat %}Dit is jou plek{% elseif seatName in takenSeats %}{{ occupiedSeats[seatName] }}{% endif %}" data-toggle='tooltip'>
                                                        {{ seatName }}
                                                    </span>
                                                {% endif %}
                                            {% endif %}

                                            {% set seatNum += 1 %}
                                        </div>
                                    {% endfor %}
                                </div>


                            </div>

                        {% endfor %}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>