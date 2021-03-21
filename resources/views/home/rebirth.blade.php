@extends('home.app')

@section('title', '偶像轉生')

@section('styles')
    <style type="text/css">
        .table {
            margin-bottom: 1px;
        }

        .current-select {
            border-width: 3px;
            border-style: solid;
            border-image: initial;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 2px 4px;
            transition: all 0.2s ease 0s;
            cursor: default;
            border-color: var(--primary-bg-color);
        }

        .not-select {
            border: 3px solid transparent;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 2px 4px;
            transition: all 0.2s ease 0s;
        }

        .not-select:hover {
            border-color: rgb(119, 119, 119);
        }

        .character-frame {
            width: 50%;
        }

        .character-frame:first-child {
            width: 50%;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            border-left: 1px solid var(--border-color);
        }

        .character-frame:nth-child(2) {
            width: 50%;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
        }

        .character-frame:nth-child(odd) {
            width: 50%;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            border-left: 1px solid var(--border-color);
        }

        .character-frame:nth-child(even) {
            width: 50%;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
        }

        @media screen and (max-width: 640px) {
            .character-frame {
                width: 100%;
                border-right: 1px solid var(--border-color);
                border-left: 1px solid var(--border-color);
                border-bottom: 1px solid var(--border-color);
            }

            .character-frame:nth-child(odd) {
                width: 100%;
            }

            .character-frame:nth-child(even) {
                width: 100%;
            }

            .character-frame:last-child {
                width: 100%;
            }
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        var selected_character = '';

        const rebirth = $('#rebirth');

        const select_character = (character_name, elem) => {
            this.selected_character = character_name;

            $('div.current-select').switchClass('current-select', 'not-select');

            elem.className = 'current-select';
        }

        rebirth.on('click', () => {
            axios.patch('{{ Route('api.rebirth') }}', {
                character_name: selected_character,
            }).then(({message, status, Back_Home}) => {
                if (status) {
                    if (Back_Home) {
                        document.location.href = "{{ Route('home.index') }}";
                    }
                } else {
                    setMsg(message);
                }
            }).catch((err) => {
                rebirth.prop('disabled', false);
                show_error_msg(err, $('#Msg'));
            });
        })

        $(function () {
            $('div.character-frame').first().children().trigger('click');
        })
    </script>
@endsection

@section('content')
    <div class="tb">
        <h3>選擇轉生偶像</h3>
        <div style="display: flex;flex-wrap: wrap;">
            @foreach($own_characters as $character)
                <div class="character-frame">
                    <div class="not-select"
                         onclick="select_character('{{ $character->GameCharacter['en_name'] }}', this)">
                        <div class="img-big">
                            <picture>
                                <img
                                    src="{{ asset('img/characters/' . $character->GameCharacter->img_file_name . '.jpg') }}"
                                    alt="{{ $character->character_name }}">
                            </picture>
                        </div>
                        <div style="flex: 1 1;">
                            <p style="margin-top: 1rem;">
                                {{ $character->GameCharacter->introduction }}
                            </p>
                        </div>
                        <table class="table">
                            <tbody class="thead-light">
                            <tr>
                                <th class="text-center">英文名稱</th>
                                <td>{{ $character->GameCharacter->en_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">中文名稱</th>
                                <td>{{ $character->GameCharacter->tc_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">生命值</th>
                                <td>{{ $character->GameCharacter->vitality }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">精力</th>
                                <td>{{ $character->GameCharacter->energy }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">抗壓性</th>
                                <td>{{ $character->GameCharacter->resistance }}</td>
                            </tr>
                            <tr>
                                <th class="text-center">魅力</th>
                                <td>{{ $character->GameCharacter->charm }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="Msg" style="display:none;" id="Msg"></div>
    </div>
    <button class="btn btn-primary btn-block" id="rebirth" style="margin: 0 0;">轉生</button>
@endsection
