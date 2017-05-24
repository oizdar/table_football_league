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


}

let form = new Form()
form.renderInputs($('#players').val());

