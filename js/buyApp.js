window.onload = function() {
    let buyForm = new BuyForm('#buyForm');
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        uiLibrary: 'bootstrap4',
        change: function(e){
            buyForm.chooseDate(e);
        }
    });
    document.getElementById('seanceChooser').addEventListener("change", function(e){
        buyForm.chooseSeance(e);
    });
    document.getElementById('buyButton').addEventListener("click", function(){
        buyForm.submit();
    });
};