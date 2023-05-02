<script>
    let request;
    let item_age_limit;

    function showAgeAgreeMessage(age_limit) {
        item_age_limit = age_limit;
        $("#age_agree_modal_dialog .limit-age").html(age_limit);
        $("#age_agree_modal_dialog").modal('show');
    }


    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function save_cart(add_agree) {
            var data = request;
            if (add_agree) {
                data['age_limit'] = item_age_limit;
            }
            $.ajax({
                url: '{{ route('cart.change') }}',
                type: 'POST',
                datatype: 'json',
                data: data,
                beforeSend: function(){
                    dispLoading();
                }
            }).then(
                function(response){
                    if(response.errors){
                        let errorText =  '';
                        if(response.errors.item_id){
                            Object.keys(response.errors.item_id).forEach(function (key) {
                                errorText += response.errors.item_id[key] + '\n';
                            });
                        }
                        if(response.errors.amount){
                            Object.keys(response.errors.amount).forEach(function (key) {
                                errorText += response.errors.amount[key] + '\n';
                            });
                        }
                        if(response.errors.can_change_amount){
                            Object.keys(response.errors.can_change_amount).forEach(function (key) {
                                errorText += response.errors.can_change_amount[key] + '\n';
                            });
                        }
                        if(response.errors.exsits_stock){
                            Object.keys(response.errors.exsits_stock).forEach(function (key) {
                                errorText += response.errors.exsits_stock[key] + '\n';
                            });
                        }
                        if(response.errors.sale_limit){
                            Object.keys(response.errors.sale_limit).forEach(function (key) {
                                errorText += response.errors.sale_limit[key] + '\n';
                            });
                        }
                        if(response.errors.age_limit){
                            var age_limit = response.errors.age_limit[0];
                            showAgeAgreeMessage(age_limit);
                            removeLoading();
                            return;
                        }
                        if('' !== errorText){
                            alert(errorText);
                            removeLoading();
                            return;
                        }
                    }

                    removeLoading();
                    showCartMessage(response);
                },
                function(XMLHttpRequest, textStatus, errorThrown){
                    alert('保存に失敗しました。\n画面を更新して表示しなおしてください。');
                    removeLoading();
                }
            );
        }

        $(document).on("click", ".js-add-cart", function () {

            let item_id = parseInt($('#item_id').val());
            let amount = $(this).parent().parent().find("select[name=item_quantity]").val();
            amount = parseInt(amount);
            let class_one = parseInt($('#class_one').val());

            if (class_one) {
                window.location.href = "/cart/agree/" + item_id + "/" + amount;
                return;
            }

            request = {
                'item_id':item_id,
                'amount':amount,
                'is_add': 1,
            };

            save_cart(false);
        });

        $('#js-colorbox').colorbox({
            inline:true,
            width:"76%",
            maxWidth:"90%",
            maxHeight:"90%",
            opacity: 0.7,
        });
        $(".back").click(function(){
            parent.$.fn.colorbox.close(); return false;
        });

        $(".add-cart-button").click(function () {
            var amount_element = $(this).parent().parent().parent().find("select").get(0);
            let item_id = parseInt($(this).attr('data'));
            let amount = parseInt($(amount_element).val());
            let class_one = parseInt($(this).attr('class-one'));

            if (class_one) {
                window.location.href = "/cart/agree/" + item_id + "/" + amount;
                return;
            }

            request = {
                'item_id':item_id,
                'amount':amount,
                'is_add': 1,
            };

            save_cart(false);

        });

        $("#age_agree_modal_dialog .btn-agree").click(function() {
            $("#age_agree_modal_dialog").modal('hide');
            save_cart(true);
        });
    });
</script>
