<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>

    $(document).ready(function () {

        $('#table-tbody').sortable({
            helper: fixWidthHelper,
            update: function (event, ui) {
                $('#sorting-update-form').submit();
            }
        }).disableSelection();
        
        $('.table-tbody').sortable({
            helper: fixWidthHelper,
            update: function (event, ui) {
                $('#sorting-update-form').submit();
            }
        }).disableSelection();


        function fixWidthHelper(e, ui) {
            ui.children().each(function () {
                $(this).width($(this).width());
            });
            return ui;
        }
    });

</script>
