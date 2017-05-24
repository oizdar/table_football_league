class LeagueList
{
    constructor(data) {
        this.data = data;
        this.list = $('<div class="list-group"></div>');
        this.item= $('<button class="list-group-item"></button>');
        this.header = $('<h4 class="list-group-item-heading"></h4>');
        this.description = $('<p class="list-group-item-text"></p>');
    }

    renderList() {
        if(this.data.length === 0) {
            this.item.html(this.description.text('Not found any leagues. Please create one.'));
            this.item.addClass('disabled');
            this.list.append(this.item);
        } else {
            this.data.forEach(function(league) {
                this.list.append(this.createItem(league))
            }.bind(this));
        }

        $('#main-container').html(this.list);
    }

    createItem(league) {
        let item = this.item.clone();
        item.append(this.header.text(league.name).clone());
        item.append(this.description.text(league.description).clone());
        item.on('click', function() {return this.showMatches(league.id)}.bind(this));
        return item;
    }

    showMatches(id) {
        Page.renderMatchesList(data)
    }
}
