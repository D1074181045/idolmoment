{{ Html::script( mix('js/app.min.js') ) }}
{{ Html::script( mix('js/vendor.js') ) }}
{{ Html::script( mix('js/manifest.js') ) }}

<script type="text/javascript">
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

    let key = "";

    const surprises = [
        {'keyCode': "38384040373937396665", 'character-name': "Gawr Gura"}, // 上上下下左右左右BA
        {'keyCode': "383840403737393966656665", 'character-name': "Akai Haato"}, // 上上下下左左右右BABA
        {'keyCode': "7889657269767679", 'character-name': "Sakuramiko"}, // nyahello
        {'keyCode': "82828282", 'character-name': "Uruha Rushia"}, // rrrr
        {'keyCode': "85668566", 'character-name': "Inugami Korone"} // ubub
    ];

    document.addEventListener('keyup', function (e) {
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
    })

    Echo.private('unlock-character-channel-{{ $self_name_encrypt }}')
        .listen('.unlock-character-event', ({Character}) => {
            unlock_character_message(Character);
        });

    if (localStorage.dark_theme === "true" && {{ $dark_theme }}) {
        document.getElementsByTagName('body')[0].className = 'dark';
    } else if (localStorage.dark_theme === "true") {
        document.getElementById('lightSwitch').click();
    } else if ({{ $dark_theme }}) {
        document.getElementsByTagName('body')[0].className = 'dark';
    }
</script>

@yield('scripts')
