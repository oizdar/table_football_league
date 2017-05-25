class Form {
    constructor() {
        this.form = $('#create-league-form');
        this.inputGroup = '<div class="input-group" id="players_logins_group"></div>';
        this.addon = '<span class="input-group-addon">Number of players</span>';
        this.input = '<input type="text" name="player[]" class="form-control" required>';
        this.setEvents();
    }
    setEvents() {
        let playersNumberInput = $('#players');
        playersNumberInput.keypress(function (e) {
            e.preventDefault();
        });
        playersNumberInput.on('change', function() {
            this.updateInputs(playersNumberInput.val());
        }.bind(this));
        this.form.on('submit', function(e) {
            this.createLeague();
            e.preventDefault();
        }.bind(this))
    }

    renderInputs(players) {
        for(let x = 0; x < players; x++) {
            this.appendInput(x+1);
        }
        this.moveButtonAtTheEnd();
    }

    appendInput(x) {
        let input = $(this.inputGroup);
        input.append($(this.addon).text('Player '+x+': '));
        input.append($(this.input));
        this.form.append(input);
    }

    updateInputs(players) {
        let actualInputsNumber = $('[name="player[]"]').length;
        if(players > actualInputsNumber) {
            this.addInputs(players-actualInputsNumber, actualInputsNumber);
        }
        if(players < actualInputsNumber) {
            this.removeInputs(actualInputsNumber-players);
        }
        this.moveButtonAtTheEnd()
    }

    addInputs(number, lastInput) {
        for(let x = 0; x < number; x++) {
            lastInput++;
            this.appendInput(lastInput);
        }
    }

    removeInputs(number) {
        $('[name="player[]"]').slice(-number).parent().remove();
    }

    moveButtonAtTheEnd() {
        $('[type="submit"]').remove().insertAfter($('.input-group:last'));
    }

    createLeague() {
        let data = {
            name: this.form.find('[name="name"]').val(),
            description: this.form.find('[name="description"]').val(),
            players: this.form.find('[name="player[]"]').map(function() {return $(this).val();}).get()
        };
        $.ajax({
            method: 'POST',
            url: '/api/league',
            data: data,
            success: function (res) {
                if(res.code === 'OK') {
                    Page.renderMatchesList(res.data);
                }
            },
            error: function (error) {
                error = JSON.parse(error.responseText).data.error;
                Page.showError(error, false);
            }
        })
    }
}
let form = new Form();
form.renderInputs($('#players').val());

