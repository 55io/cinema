window.onload = function() {
    let tabs = document.querySelectorAll('[data-ajax="report"]');

    Array.from(tabs).forEach(tab => {
        tab.addEventListener('click', function(e) {
            let reportId = e.target.getAttribute("data-report");
            let reportSelector = e.target.getAttribute("href");
            if(!document.querySelector(reportSelector + ' table')) {
                let myReport = new Report(reportSelector, reportId);
                myReport.generate();
            }
        });
    });
    let addFilmButton = document.getElementById('addFilmButton');
    addFilmButton.addEventListener('click', function (e) {
        let filmDuration = document.getElementById('filmDuration').value;
        let filmName = document.getElementById('filmName').value;
        let param = {code:'addFilm', name: filmName, duration: filmDuration};
        let myRequest = new Request('/ajax/generatorHandler.php', param, 'POST');
        const success = (data) => {console.log(data)};
        myRequest.execute(success);
    });
    let generateSheduleButton = document.getElementById('generateButton');
    generateSheduleButton.addEventListener('click', function (e) {
        let param = {code:'generate'};
        let myRequest = new Request('/ajax/generatorHandler.php', param, 'POST');
        const success = (data) => {console.log(data)};
        myRequest.execute(success);
    });
};