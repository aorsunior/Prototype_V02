$( document ).tooltip();
$( "#datepicker" ).datepicker();
$( "#dialog" ).dialog();
new DataTable('#datatable');

function confirm_del(url)
{
    if (window.confirm("ต้องการเปลี่ยนสถานะหรือไม่"))
    {
        window.open(url,"_self")
    }
}