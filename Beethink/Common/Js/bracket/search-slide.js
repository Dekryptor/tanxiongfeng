$(document).ready(function (){
    $('#search-btn').click(function (){
        var obj= $(this).parent().parent().children().eq(1);
        $(obj).slideToggle(100);
    });
});