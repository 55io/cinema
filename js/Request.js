class Request {

    constructor(url, param, type = 'GET') {
        this.url = url;
        this.param = param;
        this.type = type;
    }

    execute(success = (data) => data, error = (code, textstatus) => console.log(`${code} ${textstatus}`)) {
        $.ajax({
            type: this.type,
            url: this.url,
            tryCount : 0,
            retryLimit : 3,
            data: {
                param: this.param
            },
            success: (data) => {
                success(data);
            },
            error : function(xhr, textStatus) {
                if (textStatus === 'timeout') {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                    return;
                }
                error(xhr.code, textStatus);
            }
        });
    }
}