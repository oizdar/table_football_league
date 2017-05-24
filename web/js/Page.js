class Page
{
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
                console.log(error);
            }
        });

    }

    static renderCreateLeagueForm() {
        let navbar = new NavBar();
        navbar.nav.default[0].active = false;
        navbar.nav.default[1].active = true;
        navbar.renderDefault();

        $('#main-container').load('createForm.html');

    }
}
