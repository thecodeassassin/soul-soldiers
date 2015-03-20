$(function(){
    $('.tournament-info').find('ul').sortable({
        axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable('serialize', {attribute: 'data-player-id'}),
                tournamentId = $(this).attr('data-tournament-id');

            // POST to server using $.post or $.ajax
            $.ajax({
                data: data,
                type: 'POST',
                url: '/tournament/updateRank/'+tournamentId
            });
        }
    });
});