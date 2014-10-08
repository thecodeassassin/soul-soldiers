{% set seatImagePosX = event.seatImagePosX %}
{% set seatImagePosY = event.seatImagePosY %}
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
            <div class="seatmap-wrapper" style="background-image: url({{ url('img/seatmaps/' ~ event.systemName ~ '.jpg') }})">

                {% set blockSize = event.tableBlockSize %}
                {% set rowSize = event.tableRowSize %}

                {% set seatNum = 1 %}
                <div class="seatmap" style="position: absolute; left: {{ seatImagePosX }}px; top: {{ seatImagePosY }}px">

                    {% set numRows = (numSeats / rowSize * blockSize) / 2 %}
                    {% set tableCount = 0 %}
                    {% set blockSizePx =  blockSize * 25 + 2.5 %}

                    <div class="seat-row-wrapper" style="width: {{ blockSizePx * rowSize + (15 * numRows) }}px">


                        {% for row in 0..numRows if seatNum < numSeats %}

                            <div class="seat-table" style="width:{{ blockSizePx }}px">

                                <div class="seat-row">

                                    {% for seat in 1..blockSize*tableSize %}
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
                                                <span title="{% if seatName == userSeat %}Dit is jou plek{% elseif seatName in takenSeats %}{{ seatMap[seatName] }}{% endif %}" data-toggle='tooltip'>
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