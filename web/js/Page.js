class Page
{
    static get ALERT_PANEL() {
        return '<div class="alert alert-danger"></div>';
    }

    static renderDefault() {
        let navbar = new NavBar();
        navbar.renderDefault();
        $.ajax({
            type: 'GET',
            url: '/api',
            success: function (res) {
                if(res.code === 'OK') {
                    let leagueList = new LeagueList(res.data);
                    leagueList.renderList();
                }
            },
            error: function (error) {
                error = JSON.parse(error.responseText).data.error;
                Page.showError(error);
            }
        });
    }

    static renderCreateLeagueForm() {
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

    static showError(error, clearContent = true) {
        let navbar = new NavBar();
        navbar.nav.default[0].active = false;
        navbar.renderDefault();
        if(clearContent === true) {
            $('#main-container').html('');
        }
        $('#main-container').prepend($(Page.ALERT_PANEL).text(error));
    }

    static renderMatchesList(data) {
        let navbar = new NavBar();
    }
}
