<nav class="navbar navbar-expand-md navbar-light bg-dark-blue fixed-top" style="min-width: 400px;padding: 0;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"
            style="width: 100px;">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent"
         style="user-select: none;padding-top: 0.5rem;padding-bottom: 0.5rem;">
        <div class="dark-swift-button">
            <input class="swift-btn_toggle" type="checkbox" id="lightSwitch"
                {{ $dark_theme === 'true' ? 'checked' : '' }}
            >
            <label for="lightSwitch" style="margin-bottom: 0;">Toggle</label>
        </div>
        <ul class="nav navbar-nav navbar-brand" style="margin: auto;">
            <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'index' }" exact>我的偶像</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'active-idol' }" exact>活躍偶像</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'rebirth' }" exact>偶像轉生</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="{ name: 'chatroom' }" exact>聊天室</router-link>
            </li>
        </ul>
        @if ($self_name)
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <span id="navbarDropdown" class="nav-link dropdown-toggle nav-link-red" role="button"
                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                          style="background-color: #ffffff00;">
                        {{ $self_name }}
                    </span>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <router-link tag="span" style="cursor: pointer;" class="dropdown-item nav-link-red" :to="{ name: 'update-password' }" exact>修改密碼</router-link>
                        <span class="dropdown-item nav-link-red" id="logout" style="cursor: pointer;">登出</span>
                    </div>
                </li>
            </ul>
        @endif
    </div>
</nav>
