__BRACKET_OPTS = {
    save:
    /* Called whenever bracket is modified
     *
     * data:     changed bracket object in format given to init
     * userData: optional data given when bracket is created.
     */
        function saveFn(data, userData) {
            var json = $.toJSON(data);
            $.post('/admin/updateTournamentData', { data: json, tournamentId: __TOURNAMENT_ID }, function(data) {
                console.log(data);
            });
        },
    init: __BRACKET_DATA,
    isDoubleElimination: __IS_DOUBLE_ELIMINATION,
    disableTeamNameEdit: true,
    disableEditButtons: true
};