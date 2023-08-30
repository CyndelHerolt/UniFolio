import $ from 'jquery';
import 'datatables.net-dt';

$(document).ready(function() {
    $('#evalBilan').DataTable(
        {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/French.json"
            }
        }
    );
});