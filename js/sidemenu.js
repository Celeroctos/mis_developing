
$(document).ready(function() {
    $('#mainSideMenu').find('a[href$="#"]').on('click', function() {
        return $(this).parents('li:eq(0)').find('ul:eq(0)').slideToggle() ? false : false;
    }).parents('li.active').find('ul:eq(0)').css('display', 'block');
});