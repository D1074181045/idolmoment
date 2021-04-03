{{ Html::script( mix('js/app.home.min.js') ) }}
{{ Html::script( mix('js/vendor.js') ) }}
{{ Html::script( mix('js/manifest.js') ) }}

<script type="text/javascript">
    if (localStorage.dark_theme === "true" && {{ $dark_theme }}) {
        document.getElementsByTagName('body')[0].className = 'dark';
    } else if (localStorage.dark_theme === "true") {
        document.getElementById('lightSwitch').click();
    } else if ({{ $dark_theme }}) {
        document.getElementsByTagName('body')[0].className = 'dark';
    }
</script>

@yield('scripts')
