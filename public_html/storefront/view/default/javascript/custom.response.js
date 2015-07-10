$('document').ready(function () {

    // Toggle
    $('.togglehandle').click(function () {
        $(this).toggleClass('active');
        $(this).next('.toggledata').slideToggle();
    });

    // Checkout steps
    $('.checkoutsteptitle').addClass('down').next('.checkoutstep').fadeIn()
    $('.checkoutsteptitle').on('click', function () {
        $("select, input:checkbox, input:radio, input:file").css('display', 'blcok');
        $(this).toggleClass('down').next('.checkoutstep').slideToggle()
    });

    // Prdouctpagetab
    $('#myTab a:first').tab('show')
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    if(self.document.location.hash=='#review'){
        $('#myTab a:eq(1)').click();
    }

})

var openModalRemote = function (id, url) {
	var modal = $(id);
	var modalBody = $(id +' .modal-body');
	modal.on('show.bs.modal', function () {
	    modalBody.load(url)
	}).modal();
}

var goBack = function () {
    window.history.back();
}