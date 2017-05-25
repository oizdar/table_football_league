class League
{
    constructor(leagueId) {
        this.leagueId = leagueId;
        this.tableBodyMatches = $('#matches_body');
        this.tableBodyScores = $('#scores_body');
        this.row = '<tr></tr>';
        this.th = '<th></th>';
        this.td = '<td></td>';
        this.inputGroup = '<div class="input-group input-group-sm"></div>';
        this.addon = '<span class="input-group-addon">Format: (1-1 or 1:1)</span>';
        this.input = '<input type="text" class="col-xs-2 form-control" required>';
        this.button = '<span class="input-group-btn"><button class="btn btn-info" type="button">Update!</button> </span>'
    }

    renderMatchesTable(data) {
        let orderNumber = 1;

        data.forEach(function(match) {
            let row = $(this.row);
            let th = $(this.th);
            th.text(orderNumber+'.');
            th.attr('scope', 'row');
            row.append(th);
            row.append($(this.td).text(match.teams.TeamA.PlayerA));
            row.append($(this.td).text(match.teams.TeamA.PlayerB));
            row.append($(this.td).text(match.teams.TeamB.PlayerA));
            row.append($(this.td).text(match.teams.TeamB.PlayerB));
            if(match.score === null) {
                row.append(this.createScoreForm(match.id));
            } else {
                row.append($(this.td).text(match.score));
            }

            this.tableBodyMatches.append(row);
            orderNumber++;
        }.bind(this));

        $('#league-scores-link').on('click', function() {
            Page.renderLeagueScores(this.leagueId, $('#navbar-text').text());
        }.bind(this))
    }

    createScoreForm(matchId) {
        let inputId = 'match-score-'+matchId;
        let td = $(this.td);
        let inputGroup = $(this.inputGroup);
        inputGroup.append($(this.input).attr('id', inputId));
        inputGroup.append(this.addon);

        let button = $(this.button);
        let self = this;
        button.on('click', function () {
            let input = $('#'+inputId);
            let score = input.val();
            if(score === '') {
                input.focus();
                input.addClass('invalid-input-value');

                Page.showError('Score field should not be empty.', false);
                return;
            }
            $.ajax({
                method: 'POST',
                url: '/api/'+self.leagueId+'/match/'+matchId,
                data: {
                    score: score
                },
                success: function (res) {
                    if (res.code === 'OK') {
                        inputGroup.fadeOut('slow', function() {
                            inputGroup.remove();
                            td.text(res.data.team_1_score + ' - ' + res.data.team_2_score)
                        });
                    }
                },
                error: function (error)
                {
                    input.focus();
                    input.addClass('invalid-input-value');
                    error = JSON.parse(error.responseText).data.error;
                    Page.showError(error, false);
                }
            })
        });
        inputGroup.append(button);
        td.append(inputGroup);
        return td;
    }

    renderScoresTable(data) {
        let orderNumber = 1;
        for(let key in data) {
            if(data.hasOwnProperty(key)) {
                let row = $(this.row);
                let th = $(this.th);
                th.text(orderNumber+'.');
                th.attr('scope', 'row');
                row.append(th);
                row.append($(this.td).text(key));
                row.append($(this.td).text(data[key].points));
                row.append($(this.td).text(data[key].matches));

                if(orderNumber===1) {
                    row.addClass('success');
                }
                if(orderNumber===2) {
                    row.addClass('active')
                }
                if(orderNumber===3) {
                    row.addClass('warning')
                }

                this.tableBodyScores.append(row);
                orderNumber++;
            }
        }

        $('#league-scores-link').on('click', function() {
            Page.renderLeagueMatches(this.leagueId, $('#navbar-text').text());
        }.bind(this))
    }
}
