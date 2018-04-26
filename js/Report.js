class Report {
    constructor(selector, number) {
        this.selector = selector;
        this.number = number;
        this.description = null;
        this.resultTable = null;
    }

    generate() {
        if (this.resultTable === null) {
            const reportUrl = './reportHandler.php';
            const onSuccess = (data) => {
                let parsedResult = JSON.parse(data);
                this.resultTable = Report.buildTable(parsedResult['table']);
                this.description = Report.buildDescription(parsedResult['description']);
                this.render();
            };
            let param = {reportNumber: this.number};
            let myRequest = new Request(reportUrl, param);
            myRequest.execute(onSuccess);
        }
    }

    render() {
        document.querySelector(this.selector).appendChild(this.description);
        document.querySelector(this.selector).appendChild(this.resultTable);
    }

    static buildTable(result) {
        let tableHead = Report.buildTableHead(Object.keys(result[0]));
        let tableBody = Report.buldTableBody(result);
        let table = document.createElement('table');
        table.setAttribute('class', 'table');
        table.appendChild(tableHead);
        table.appendChild(tableBody);

        return table;
    }

    static buildTableHead(colHeaders) {
        let tableHead = document.createElement('thead');
        let rowHead = document.createElement('tr');
        for(let i = 0; i < colHeaders.length; i++ ) {
            let colHead = document.createElement('th');
            colHead.setAttribute('scope', 'col');
            let colHeadText = document.createTextNode(Report.formatColName(colHeaders[i]));
            colHead.appendChild(colHeadText);
            rowHead.appendChild(colHead);
        }
        tableHead.appendChild(rowHead);
        return tableHead;
    }

    static buldTableBody(tableRows) {
        let tableBody = document.createElement('tbody');

        for(let i = 0; i < tableRows.length; i++ ) {
            let tableRow = document.createElement('tr');
            let row = Object.values(tableRows[i]);
                for(let j = 0; j < row.length; j++ ) {
                    let tableCol = document.createElement('td');
                    let colText = document.createTextNode(row[j]);
                    tableCol.appendChild(colText);
                    tableRow.appendChild(tableCol);
                }
            tableBody.appendChild(tableRow);
        }
        return tableBody;
    }

    static buildDescription(description) {
        let descriptionP = document.createElement('p');
        let descriptionText = document.createTextNode(description);
        descriptionP.appendChild(descriptionText);
        return descriptionP;
    }

    static formatColName(name) {
        return name.replace(/[_]/g, " ");
    }
}