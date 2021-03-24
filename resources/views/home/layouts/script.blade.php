{{ Html::script( mix('js/app.min.js') ) }}
{{ Html::script( mix('js/vendor.js') ) }}
{{ Html::script( mix('js/manifest.js') ) }}

<script type="text/javascript">
    $('#lightSwitch').on('click', (e) => {
        let d = new Date();
        d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000);

        let link = $('link');
        let link_swal_style = link[link.length - 1];

        if (e.target.checked) {
            $('body').prop('class', 'dark');
            document.cookie = 'dark_theme=true; expires=' + d.toString() + '; path=/';
            localStorage.dark_theme = true;
            link_swal_style.href = '{{ asset('css/sweetalert2.dark.theme.css') }}';
        } else {
            $('body').prop('class', '');
            document.cookie = 'dark_theme=false; expires=' + d.toString() + '; path=/';
            localStorage.dark_theme = false;
            link_swal_style.href = '{{ asset('css/sweetalert2.default.theme.css') }}';
        }
    })

    const unlock_character_message = (new_character_name) => {
        let character = '<div style="color: #DC3545;">' + new_character_name + '</div>';

        return Swal.fire({
            title: "已解鎖新偶像",
            html: character,
            icon: "success",
            allowOutsideClick: false
        });
    }

    const keyup_unlock_role = (character_name) => {
        axios.post('{{ Route('api.store.keyup_unlock_character') }}', {
            character_name: character_name,
        })
    }

    $("#logout").on('click', () => {
        Swal.fire({
            title: "登出",
            text: "確定登出?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '確定',
            cancelButtonText: '取消',
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed)
                document.location.href = '{{ Route('home.logout') }}';
        });
    })

    let key = "";

    const surprises = [
        {'keyCode': "38384040373937396665", 'character-name': "Gawr Gura"}, // 上上下下左右左右BA
        {'keyCode': "383840403737393966656665", 'character-name': "Akai Haato"}, // 上上下下左左右右BABA
        {'keyCode': "7889657269767679", 'character-name': "Sakuramiko"}, // nyahello
        {'keyCode': "82828282", 'character-name': "Uruha Rushia"}, // rrrr
        {'keyCode': "85668566", 'character-name': "Inugami Korone"} // ubub
    ];

    $(document).on('keyup', function (e) {
        if (e.keyCode) {
            let t = 0;

            key += e.keyCode.toString();

            surprises.forEach((surprise) => {
                if (key === surprise['keyCode']) {
                    keyup_unlock_role(surprise['character-name']);
                    return true;
                } else if (surprise['keyCode'].includes(key)) {
                    return true;
                } else {
                    t++;
                }
            });

            if (surprises.length === t)
                key = e.keyCode.toString();
        }
    });

    $(function () {
        if (localStorage.dark_theme === "true" && {{ $dark_theme }}) {
            $('body').prop('class', 'dark');
        } else if (localStorage.dark_theme === "true") {
            $('#lightSwitch').trigger('click');
        } else if ({{ $dark_theme }}) {
            $('body').prop('class', 'dark');
        }

        Echo.connector.pusher.config.auth.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
        Echo.private('unlock-character-channel-{{ $self_name_encrypt }}')
            .listen('.unlock-character-event', ({Character}) => {
                unlock_character_message(Character);
            });

        let clear_danger_msg = null;

        Echo.private('danger-channel-{{ $self_name_encrypt }}')
            .listen('.danger-event', ({message}) => {
                if (message) {
                    let danger_msg = $('#danger_msg');
                    let marquee_message = '<marquee scrollamount="10" behavior="alternate">' + message + '</marquee>';

                    if (danger_msg.length) {
                        clearTimeout(clear_danger_msg);
                        danger_msg.remove();
                        $('#app').prepend('<div id="danger_msg" class="alert-danger">' + marquee_message + '</div>');
                    } else {
                        $('#app').prepend('<div id="danger_msg" class="alert-danger">' + marquee_message + '</div>');
                    }

                    clear_danger_msg = setTimeout(() => {
                        $('#danger_msg').remove();
                    }, 10000);
                }
            });
    })
</script>

@yield('scripts')
