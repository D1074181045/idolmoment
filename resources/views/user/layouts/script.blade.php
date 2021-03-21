{{ Html::script( mix('js/app2.min.js') ) }}
{{ Html::script( mix('js/vendor.js') ) }}
{{ Html::script( mix('js/manifest.js') ) }}

<script type="text/javascript">
    const setMsg_footer = (Msg, msg_val, footer, elem = false) => {
        Msg.html(msg_val);
        footer.prop('style', 'display: block;');

        setTimeout(() => {
            $('#Msg').html("");
            footer.prop('style', 'display: none');
            if (elem)
                elem.blur();
        }, 3000)
    }
</script>

@yield('scripts')
