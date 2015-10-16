{% set seatImagePosX = seatMap.posX %}
{% set seatImagePosY = seatMap.posY %}
{% set crewSize = event.crewSize %}
{% set tableSize = 2 %}
{% set numSeats = event.maxEntries - crewSize %}

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

        <div class="seating-table">
            <div class="seatmap-wrapper" style="background-image: url({{ seatMap.url }})">

                {% set xCount = seatMap.xCount %}
                {% set yCount = seatMap.yCount %}
                {% set tableLimit = seatMap.tableLimit %}

                {% set seatNum = 1 %}
                <div class="seatmap" style="position: absolute; left: {{ seatImagePosX }}px; top: {{ seatImagePosY }}px">

                    {% set numRows = (numSeats / xCount / yCount) %}

                    {% set tableCount = 0 %}
                    {% set blockSizePx =  xCount * 25 + 2.5 %}

                    <div class="seat-row-wrapper" style="width: {{ blockSizePx * tableLimit + (25 * tableLimit)}}px">

                        {% for row in 0..numRows if seatNum < numSeats %}

                            <div class="seat-table" style="width:{{ blockSizePx }}px">

                                <div class="seat-row">

                                    {% for seat in 1..xCount*yCount %}
                                        {% if seatNum > numSeats %}{% break %}{% endif %}
                                        {% set seatName = row+1~'.'~seat %}
                                        <div class="seat {% if seatName in takenSeats %}taken{% elseif seatName == userSeat %}yours{% else %}free{% endif %}">
                                            {% if seatName not in takenSeats and seatName != userSeat and seatingAvailable %}
                                                <a href="{{ url('event/'~ event.systemName ~ '/reserve-seat/' ~ seatName ) }}"
                                                   data-toggle='tooltip'
                                                   title="Deze plek is vrij"
                                                   onclick="return reserveSeat()">
                                                    {{ seatName }}
                                                </a>
                                            {% else %}
                                                <span title="{% if seatName == userSeat %}Dit is jou plek{% elseif seatName in takenSeats %}{{ occupiedSeats[seatName] }}{% endif %}" data-toggle='tooltip'>
                                                    {{ seatName }}
                                                </span>
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