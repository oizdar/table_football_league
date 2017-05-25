class NavBar
{
    constructor(data) {
        this.navbarData = data;
        this.nav = {
            default: [
                {
                    active: true,
                    name: 'Leagues',
                    onclickFunction: 'renderDefault',
                    functionHaveParams: false,
                    glyphicon: 'glyphicon-list-alt'
                },
                {
                    active: false,
                    name: 'Create League',
                    onclickFunction: 'renderCreateLeagueForm',
                    functionHaveParams: false,
                    glyphicon: 'glyphicon-edit'
                }
            ],
            league: [
                {
                    active: false,
                    name: 'Back',
                    onclickFunction: 'renderDefault',
                    functionHaveParams: false,
                    glyphicon: 'glyphicon-chevron-left'
                },
                {
                    active: true,
                    name: 'Matches',
                    onclickFunction: 'renderLeagueMatches',
                    functionHaveParams: true,
                    glyphicon: 'glyphicon-tags'
                },
                {
                    active: false,
                    name: 'Scores',
                    onclickFunction: 'renderLeagueScores',
                    functionHaveParams: true,
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

    createItem(element, data = []) {
        let item = $(this.navbarElement);
        if(element.active === true) {
            item.addClass('active');
            item.on('click', function(e) {
                e.preventDefault();
            });
        } else {
            item.on('click', function(e) {
                e.preventDefault();
                let params = data.join('","');
                eval('Page.'+element.onclickFunction+'("'+params+'");');
            });
        }
        if(element.glyphicon !== false) {
            let glyphicon = $(this.glyphicon);
            glyphicon.addClass(element.glyphicon);
            item.find('a').append(glyphicon);
        }
        item.find('a').append(' '+element.name);
        return item;
    }

    render(navbarName, leagueName) {
        let navbarList = $(this.navbarList);
        this.nav[navbarName].forEach(function(element) {
            if(element.functionHaveParams === true) {
                let item = this.createItem(element, this.navbarData);
                navbarList.append(item);
            } else {
                let item = this.createItem(element);
                navbarList.append(item);
            }
        }.bind(this));
        this.navbarContainer.append(this.header);
        this.navbarContainer.append(navbarList);
        this.navbarContainer.append(navbarList);
        this.navbarContainer.append($(this.rightText).text(leagueName));
        $('#navbar').html(this.navbarContainer);
    }
}
