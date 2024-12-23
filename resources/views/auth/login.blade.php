    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Signix Login</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100">
        <div class="min-h-screen flex justify-center items-center p-4">
        <div class="bg-white shadow-lg rounded-lg flex flex-col md:flex-row overflow-hidden max-w-4xl w-full">
            <div class="w-full md:w-1/2 bg-blue-50 hidden md:flex md:items-center md:justify-center">
                <img src="{{ asset('images/gambar_login.png') }}" alt="Building" class="w-full h-full object-cover object-center">
                </div>

            <div class="w-full md:w-1/2 p-6 md:p-10 relative">
                <div class="absolute inset-0 md:hidden">
                    <img src="{{ asset('images/gambar_login.png') }}" alt="Building" class="w-full h-full object-cover object-center opacity-5">
                    </div>

                <div class="relative z-10">
                    <div class="w-32 md:w-40 mb-8 mx-auto md:mx-0">
                        <img src="{{ asset('images/logo_signix.png') }}" alt="Logo" class="w-full h-auto object-contain">
                    </div>

                    <form action="{{ route('login.submit') }}" method="POST" onsubmit="return validateForm()" class="mt-4">
                        @csrf

                        <div class="mb-4">
                        <label for="role" class="block mb-2 text-gray-600 text-sm md:text-base">Masuk Sebagai</label>
                            <div class="flex items-center border border-gray-300 rounded-md bg-white">
                                <span class="text-gray-500 pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <select id="role" name="role" class="w-full px-3 py-2 text-sm md:text-base text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded-md bg-transparent" onchange="toggleInputField()">
                                    <option value="">-</option>
                                    <option value="ormawa">Ormawa</option>
                                    <option value="dosen">Dosen</option>
                                </select>
                            </div>
                        </div>

                        @if ($errors->has('login'))
                        <p class="text-red-600 mb-4 text-sm">{{ $errors->first('login') }}</p>
                        @endif

                    <div id="emailField" class="mb-4 hidden">
                        <label for="email" class="block mb-2 text-gray-600 text-sm md:text-base">Email</label>
                            <div class="flex items-center border border-gray-300 rounded-md bg-white">
                                <input type="email" name="email" id="email" class="w-full px-3 py-2 text-sm md:text-base text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded-md bg-transparent">
                            </div>
                        </div>

                        <div id="nimField" class="mb-4" style="{{ old('role') === 'ormawa' ? 'display: block;' : 'display: none;' }}">
                        <label for="nim" class="block mb-2 text-gray-600 text-sm md:text-base">NIM</label>
                            <div class="flex items-center border border-gray-300 rounded-md bg-white">
                                <input type="text" name="nim" id="nim" value="{{ old('nim') }}" class="w-full px-3 py-2 text-sm md:text-base text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded-md bg-transparent">
                            </div>
                        </div>

                        <div id="nipField" class="mb-4" style="{{ old('role') === 'dosen' ? 'display: block;' : 'display: none;' }}">
                        <label for="nip" class="block mb-2 text-gray-600 text-sm md:text-base">NIP</label>
                            <div class="flex items-center border border-gray-300 rounded-md bg-white">
                                <input type="text" name="nip" id="nip" value="{{ old('nip') }}" class="w-full px-3 py-2 text-sm md:text-base text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded-md bg-transparent">
                            </div>
                        </div>

                        <div class="mb-6" id="passwordField" style="{{ old('role') ? 'display: block;' : 'display: none;' }}">
                        <label for="password" class="block mb-2 text-gray-600 text-sm md:text-base">Password</label>
                            <div class="flex items-center border border-gray-300 rounded-md bg-white">
                                <span class="text-gray-500 pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input id="password" name="password" type="password" placeholder="Masukkan Kata sandi" class="w-full px-3 py-2 text-sm md:text-base text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 rounded-md bg-transparent">
                                <button type="button" onclick="togglePasswordVisibility()" class="text-gray-500 pr-3 focus:outline-none">
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    <div id="roleAlert" class="text-red-600 mb-4 text-sm hidden">
                            Please select a role before submitting the form.
                        </div>

                        <div class="flex justify-end mb-6" id="forgotPasswordLink" style="display: none;">
                            <a href="javascript:void(0);" onclick="toggleForgotPassword()" class="text-sm text-blue-600">Lupa kata sandi?</a>
                        </div>

                    <button type="submit" id="submitButton" class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition text-sm md:text-base hidden">Masuk</button>
                    </form>

                    <p class="text-xs text-gray-500 mt-4 text-center">
                        Dengan menggunakan layanan kami, Anda berarti setuju atas <a href="#" class="text-blue-600">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600">Kebijakan Privasi</a> Signix
                    </p>
                </div>
            </div>
        </div>
                </div>

    <div id="forgotPasswordSection" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center p-4">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg max-w-sm w-full mx-4">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.1.9-2 2-2s2 .9 2 2-2 2-2 2-2-.9-2-2zm0 0c0-1.1-.9-2-2-2s-2 .9-2 2 2 2 2 2 2-.9 2-2zm0 0c0-1.1.9-2 2-2s2 .9 2 2-2 2-2 2-2-.9-2-2zm0 0c0-1.1-.9-2-2-2s-2 .9-2 2 2 2 2 2 2-.9 2-2z" />
                </svg>
            </div>
            <h2 class="text-center text-lg font-semibold mb-4">Kesulitan masuk?</h2>
            <p class="text-center text-gray-600 mb-4 text-sm md:text-base">Masukkan email, telepon, atau nama pengguna Anda dan kami akan mengirimkan tautan untuk masuk kembali ke akun Anda.</p>
            <input type="text" placeholder="Email, Telepon, atau Nama Pengguna" class="w-full px-3 py-2 mb-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm md:text-base">
            <button class="w-full bg-blue-600 text-white py-2 rounded-md font-semibold hover:bg-blue-700 transition text-sm md:text-base">Kirim tautan masuk</button>
            <p class="text-center text-sm text-gray-600 mt-4">Tidak bisa mengatur ulang kata sandi Anda?</p>
            <button onclick="toggleForgotPassword()" class="w-full mt-4 bg-gray-200 text-gray-700 py-2 rounded-md font-semibold hover:bg-gray-300 transition text-sm md:text-base">Kembali ke login</button>
        </div>
    </div>

        <script>
            function toggleInputField() {
                var role = document.getElementById('role').value;
                document.getElementById('emailField').style.display = 'none';
                document.getElementById('nimField').style.display = (role === 'ormawa') ? 'block' : 'none';
                document.getElementById('nipField').style.display = (role === 'dosen') ? 'block' : 'none';
                document.getElementById('passwordField').style.display = role ? 'block' : 'none';
                document.getElementById('submitButton').style.display = role ? 'block' : 'none';
                document.getElementById('forgotPasswordLink').style.display = role ? 'block' : 'none';
            }

            function validateForm() {
                var role = document.getElementById('role').value;
                var roleAlert = document.getElementById('roleAlert');
                if (!role) {
                    roleAlert.style.display = 'block';
                return false;
                }
                roleAlert.style.display = 'none';
            return true;
            }

            function togglePasswordVisibility() {
                var passwordInput = document.getElementById('password');
                var eyeIcon = document.getElementById('eyeIcon');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = '<path d="M17.94 17.94A10.97 10.97 0 0112 20c-5.52 0-10-4.48-10-10S6.48 0 12 0c2.61 0 5.01 1.01 6.94 2.94M1 1l22 22"></path>';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                }
            }

            function toggleForgotPassword() {
                var forgotPasswordSection = document.getElementById('forgotPasswordSection');
                forgotPasswordSection.classList.toggle('hidden');
            }
        </script>
    </body>
    </html>
