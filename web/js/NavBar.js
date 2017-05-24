class NavBar
{
    constructor() {
        this.nav = {
            default: [
                {
                    active: true,
                    name: 'Leagues',
                    onclickFunction: 'renderDefault'
                },
                {
                    active: false,
                    name: 'Create League',
                    onclickFunction: 'renderCreateLeagueForm'
                }
            ]
        };

        this.header = '<div class="navbar-header"><a href="/" class="navbar-brand">Table Footbal Leagues</a></div>';
        this.navbarContainer = $('<div class="collapse navbar-collapse"></div>');
        this.navbarList = '<ul class="nav navbar-nav"></ul>';
        this.navbarElement = '<li><a href></a></li>';
        this.rightText = '<p class="navbar-text navbar-right"></p>';
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

        item.find('a').text(element.name);
        return item;
    }
}
