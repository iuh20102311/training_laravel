<x-guest-layout>
    <!-- Popup -->
    <div id="errorPopup" class="popup">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Thông báo</h2>
                <span class="close">&times;</span>
            </div>
            <div class="popup-body">
                <i class="fas fa-exclamation-circle"></i>
                <p id="errorMessage"></p>
            </div>
        </div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />


    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <div class="input-group">
            <i class="fas fa-user"></i>
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="Email" />
        </div>
        @error('email')
            <p class="text-red-500 text-xs italic mt-1" style="color:red">{{ $message }}</p>
        @enderror

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Mật khẩu" />
        </div>
        @error('password')
            <p class="text-red-500 text-xs italic mt-1" style="color:red">{{ $message }}</p>
        @enderror

        <div class="remember-me">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember">
                <span class="ms-2 text-sm">Remember me</span>
            </label>
        </div>

        <div class="submit-button">
            <x-primary-button>
                {{ __('Đăng nhập') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var error = "{{ session('error') }}";
            if (error) {
                showPopup(error);
            }

            function showPopup(message) {
                var popup = document.getElementById('errorPopup');
                var errorMessage = document.getElementById('errorMessage');
                errorMessage.textContent = message;
                popup.classList.add('show');

                var closeBtn = document.getElementsByClassName('close')[0];
                closeBtn.onclick = function () {
                    popup.classList.remove('show');
                }

                window.onclick = function (event) {
                    if (event.target == popup) {
                        popup.classList.remove('show');
                    }
                }

                // Tự động đóng popup sau 5 giây
                setTimeout(function () {
                    popup.classList.remove('show');
                }, 5000);
            }
        });
    </script>
</x-guest-layout>