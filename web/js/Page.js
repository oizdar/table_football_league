class Page
{
    static get ALERT_PANEL() {
        return '<div class="alert alert-danger" id="alert-panel">' +
            '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;' +
            '<span id="error-text"></span>' +
            '</div>';
    }

    static renderDefault()
    {
        let navbar = new NavBar();
        navbar.renderDefault();
        $.ajax({
            type: 'GET',
            url: '/api',
            success: function (res) {
                if(res.code === 'OK') {
                    let leagueList = new LeaguesList(res.data);
                    leagueList.renderList();
                }
            },
            error: function (error) {
                error = JSON.parse(error.responseText).data.error;
                Page.showError(error);
            }
        });
    }

    static renderCreateLeagueForm()
    {
        let navbar = new NavBar();
        navbar.nav.default[0].active = false;
        navbar.nav.default[1].active = true;
        navbar.renderDefault();

        $('#main-container').load('createForm.html');
        if($('#form-script').length === 0) {
            $('body').append('<script id="form-script" src="js/Form.js"></script>')
        }
        $(document).ready(function() {
            let form = new Form();
            form.renderInputs($('#players').val());
        });
    }

    static showError(error, clearContent = true)
    {
        if(clearContent === true) {
            let navbar = new NavBar();
            navbar.nav.default[0].active = false;
            navbar.renderDefault();
            $('#main-container').html('');
        }
        let alertPanel = $('#alert-panel');
        if(alertPanel.length === 0) {
            alertPanel = $(Page.ALERT_PANEL);
            alertPanel.find('#error-text').text(error);
            $('#main-container').prepend(alertPanel);
        } else {
            alertPanel.find('#error-text').text(error);
        }
    }

    static renderLeagueMatches(leagueId, leagueName)
    {
        let navbar = new NavBar([leagueId, leagueName]);
        navbar.render('league', leagueName);
        $.ajax({
            method: 'GET',
            url: '/api/'+leagueId+'/matches',
            success: function (res) {
                if(res.code === 'OK') {
                    $('#main-container').load('matchesTable.html', function() {
                        let league = new League(leagueId);
                        league.renderMatchesTable(res.data);
                    });
                }
            },
            error: function (error) {
                error = JSON.parse(error.responseText).data.error;
                Page.showError(error, false);
            }
        })
    }

    static renderLeagueScores(leagueId, leagueName) {
        let navbar = new NavBar([leagueId, leagueName]);
        navbar.nav.league[1].active = false;
        navbar.nav.league[2].active = true;
        navbar.render('league', leagueName);

        $.ajax({
            method: 'GET',
            url: '/api/'+leagueId+'/scores',
            success: function (res) {
                if(res.code === 'OK') {
                    $('#main-container').load('scoresTable.html', function() {
                        let league = new League(leagueId);
                        league.renderScoresTable(res.data);
                    });
                }
            },
            error: function (error) {
                error = JSON.parse(error.responseText).data.error;
                Page.showError(error, false);
            }
        })
    }
}
