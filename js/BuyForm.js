class BuyForm {
    constructor(selector) {
        this.selector = selector;
    }

    chooseDate(e) {
        let param = {
            code: 'get_seances',
            date: e.target.value,
        };
        let onSuccess = (data) => {
            let parsed = JSON.parse(data);
            let result_html = '';
            for (let i = 0; i < parsed.length; i++) {
                result_html += `<option value="${parsed[i]['id']}">${parsed[i]['start_time']} ${parsed[i]['name']}</option>`;
            }
            let seanceSelect = document.querySelector(this.selector + ' #seanceChooser');
            let remainingTickets = document.querySelector(this.selector + ' #remainingTickets');
            let ticketPrice = document.querySelector(this.selector + ' #ticketPrice');
            let ticketCount = document.querySelector(this.selector + ' #ticketCount');
            let buyButton = document.querySelector(this.selector + ' #buyButton');

            seanceSelect.innerHTML = result_html;
            seanceSelect.removeAttribute('hidden');
            ticketPrice.setAttribute('hidden', '');
            remainingTickets.setAttribute('hidden', '');
            ticketCount.setAttribute('hidden', '');
            buyButton.setAttribute('hidden', '');

        };
        let request = new Request("formHandler.php", param);
        request.execute(onSuccess);
    }

    chooseSeance(e) {
        let param = {
            code: 'get_seance_info',
            id: e.target.value,
        };
        let onSuccess = (data) => {
            let parsed = JSON.parse(data)[0];

            let remainingTickets = document.querySelector(this.selector + ' #remainingTickets');
            let ticketPrice = document.querySelector(this.selector + ' #ticketPrice');
            let ticketCount = document.querySelector(this.selector + ' #ticketCount');
            let buyButton = document.querySelector(this.selector + ' #buyButton');

            remainingTickets.removeAttribute('hidden');
            ticketPrice.removeAttribute('hidden');
            ticketCount.removeAttribute('hidden');
            buyButton.removeAttribute('hidden');

            remainingTickets.value = parsed['remaining_tickets'];
            ticketPrice.innerHTML = parsed['price'];
        };
        let request = new Request("formHandler.php", param);
        request.execute(onSuccess);
    }

    submit() {
        let ticketCount = $(this.selector).find('#ticketCount').val();
        let remainingTicketsCount = $(this.selector).find('#remainingTickets').val();
        let seanceId = $(this.selector).find('#seanceChooser').val();
        if (ticketCount > remainingTicketsCount || ticketCount < 1) {
            alert(`'can't buy`);
        } else {
            let param = {
                code: 'buy',
                seance_id: seanceId,
                count: ticketCount
            };
            let requestType = 'POST';
            let onSuccess = (data) => {
                $(this.selector).find('#remainingTickets').html(remainingTicketsCount - ticketCount);
                alert('Success');
            };

            let request = new Request("/ajax/formHandler.php", param, requestType);

            request.execute(onSuccess);
        }
    }
}