<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-121546653-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-121546653-1');
</script>

<script type="text/javascript" src="{{ config('app.root_path') }}/js/general/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ config('app.root_path') }}/js/general/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ config('app.root_path') }}/js/general/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ config('app.root_path') }}/js/appearsScroll/appearsScroll_pagetop.js"></script>
<script type="text/javascript" src="{{ config('app.root_path') }}/js/jquery.matchHeight.js"></script>

<script>

    let holidays = {};

    function showCartMessage(content) {
        var html = '';
        $("#cart_modal_dialog #recommend-row").html(html);

        if (html == '') {
            $("#cart_modal_dialog #cart-question").hide();
            $("#cart_modal_dialog #recommend-row").hide();
        } else {
            $("#cart_modal_dialog #cart-question").show();
            $("#cart_modal_dialog #recommend-row").show();
        }

        $("#cart_modal_dialog").modal('show');
    }
</script>
