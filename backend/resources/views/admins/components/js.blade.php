<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
<script>
    $(function () {
        $(".datepicker").datepicker(
            {
                dateFormat: 'yy-mm-dd',
                changeYear: true,
                changeMonth: true,
            }
        );
        $(function(){
            let $datepicker = $(".datepicker-period");
            let min = $datepicker.attr('min');
            let max = $datepicker.attr('max');

            $datepicker.datepicker(
                {
                    dateFormat: 'yy-mm-dd',
                    changeYear: true,
                    changeMonth: true,
                    minDate: new Date(min),
                    maxDate: new Date(max),
                }
            );

            $('.datepicker-wrapper button').click(function(){
                $(this).parent().find('input').focus();
            });
        });

        $(".js-sortable").sortable({
            cancel : '.js-not-sortable',
            update : function(){
                if($('.js-sort-run').length){
                    $(".js-sort-run").fadeIn();
                }
            }
        });
        if($('.js-sort-run').length){
            $(".js-sort-run").hide();
        }
        $('.js-sort-up').click(function(){
            $("#js-sort-exchange-form").append('<input type="hidden" name="id" value="' + $(this).data('id') + '" />');
            $("#js-sort-exchange-form").append('<input type="hidden" name="type" value="up" />');
            $("#js-sort-exchange-form").append($('#js-current-page'));
            $('#js-sort-exchange-form').submit();
        });
        $('.js-sort-down').click(function(){
            $("#js-sort-exchange-form").append('<input type="hidden" name="id" value="' + $(this).data('id') + '" />');
            $("#js-sort-exchange-form").append('<input type="hidden" name="type" value="down" />');
            $("#js-sort-exchange-form").append($('#js-current-page'));
            $('#js-sort-exchange-form').submit();
        });
        $(".js-sort-run").click(function(){
            $("#js-sort-form").append($('#js-current-page'));
            $(".js-sortable tr").each(function(index, element){
                var id = $(element).data('id');
                console.log(id);
                $("#js-sort-form").append('<input type="hidden" name="ids[]" value="' + id + '">');
            });
            $("#js-sort-form").submit();
        });

        $("#batch_btn").click(function() {
            var targets = $(".batch-checkbox:checked").map(function(){
                return $(this).val();
            }).get();

            if (targets.length == 0) {
                alert("一括処理対象を選択してください。");
            } else if (!$("#batch_action").val()) {
                alert("一括処理操作を選択してください。")
            } else {
                $("#targets").val(targets.join());
                $("#batch_form").submit();
            }

        });

    });
</script>
