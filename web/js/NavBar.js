class NavBar
{
    constructor() {
        this.nav = {
            default: [
                {
                    active: true,
                    name: 'Leagues',
                    onclickFunction: 'renderDefault',
                    glyphicon: 'glyphicon-list-alt'
                },
                {
                    active: false,
                    name: 'Create League',
                    onclickFunction: 'renderCreateLeagueForm',
                    glyphicon: 'glyphicon-edit'
                }
            ],
            league: [
                {
                    active: false,
                    name: 'Back',
                    onclickFunction: 'renderDefault',
                    glyphicon: 'glyphicon-chevron-left'
                },
                {
                    active: true,
                    name: 'Matches',
                    onclickFunction: 'renderLeagueMatches',
                    glyphicon: 'glyphicon-tags'
                },
                {
                    active: false,
                    name: 'Scores',
                    onclickFunction: 'renderLeagueScores',
                    glyphicon: 'glyphicon-king'
                }
            ]
        };

        this.header = '<div class="navbar-header"><a href="/" class="navbar-brand">Table Footbal Leagues</a></div>';
        this.navbarContainer = $('<div class="collapse navbar-collapse"></div>');
        this.navbarList = '<ul class="nav navbar-nav"></ul>';
        this.navbarElement = '<li><a href></a></li>';
        this.rightText = '<p id="navbar-text" class="navbar-text navbar-right"></p>';
        this.glyphicon = '<span class="glyphicon"></span>';
    }

    renderDefault() {
        let navbarList = $(this.navbarList);
        this.nav.default.forEach(function(element) {
            let item = this.createItem(element);
            navbarList.append(item);
        }.bind(this));
        this.navbarContainer.append(this.header);
        this.navbarContainer.append(navbarList);
        $('#navbar').html(this.navbarContainer);
    }

    createItem(element) {
        let item = $(this.navbarElement);
        if(element.active === true) {
            item.addClass('active');

        } else {
            item.on('click', function(e) {
                e.preventDefault();
                return eval('Page.'+element.onclickFunction+'()');
            });
        }
        if(element.glyphicon !== false) {
            let glyphicon = $(this.glyphicon);
            glyphicon.addClass(element.glyphicon);
            console.log(glyphicon);
            item.find('a').append(glyphicon);
        }
        item.find('a').append(' '+element.name);
        return item;
    }

    render(navbarName, leagueName) {
        let navbarList = $(this.navbarList);
        this.nav[navbarName].forEach(function(element) {
            let item = this.createItem(element);
            navbarList.append(item);
        }.bind(this));
        this.navbarContainer.append(this.header);
        this.navbarContainer.append(navbarList);
        this.navbarContainer.append(navbarList);
        this.navbarContainer.append($(this.rightText).text(leagueName));
        $('#navbar').html(this.navbarContainer);
    }
}
