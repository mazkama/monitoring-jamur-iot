<x-guest-layout>
    <!-- Auth Layout Wrapper -->
    <div class="w-full min-h-[500px] md:h-[650px] max-w-6xl mx-auto flex flex-col md:flex-row rounded-3xl overflow-hidden shadow-2xl bg-white relative z-10 border border-outline-variant/20 m-4 md:m-auto">
        
        <!-- Left Side: Login Form -->
        <div class="w-full md:w-1/2 lg:w-5/12 p-8 sm:p-12 md:p-16 flex flex-col justify-center relative z-10 bg-white">
            
            <!-- Brand Identity -->
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-white text-2xl" data-icon="energy_savings_leaf" style="font-variation-settings: 'FILL' 1;">energy_savings_leaf</span>
                </div>
                <div>
                    <h1 class="font-headline font-extrabold text-2xl text-primary tracking-tight leading-none mb-1">Mycology IoT</h1>
                    <p class="text-secondary text-xs font-bold uppercase tracking-widest opacity-80">Sistem Monitoring</p>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-8">
                <h2 class="font-headline font-black text-3xl text-on-surface mb-2">Selamat Datang</h2>
                <p class="text-secondary font-medium text-sm leading-relaxed">Masuk ke dashboard untuk memantau ekosistem jamur tiram Anda secara real-time.</p>
            </div>

            <!-- Validation Errors -->
            @if(session('status'))
                <div class="mb-6 text-sm text-primary font-bold bg-primary/10 border border-primary/20 p-4 rounded-xl flex items-start gap-2">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 text-sm text-error font-bold bg-error/10 border border-error/20 p-4 rounded-xl flex items-start gap-2">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    <span>Email atau kata sandi tidak sesuai.</span>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-primary ml-1" for="email">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-secondary group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]" data-icon="mail">mail</span>
                        </div>
                        <input class="block w-full pl-12 pr-4 py-3.5 bg-surface-container-lowest border border-outline-variant/40 rounded-xl text-on-surface font-medium placeholder:text-outline focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none shadow-sm" 
                            id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" />
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-sm font-bold text-primary" for="password">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-bold text-secondary hover:text-primary transition-colors underline decoration-secondary/30 underline-offset-2" href="{{ route('password.request') }}">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-secondary group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]" data-icon="lock">lock</span>
                        </div>
                        <input class="block w-full pl-12 pr-12 py-3.5 bg-surface-container-lowest border border-outline-variant/40 rounded-xl text-on-surface font-medium placeholder:text-outline focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none shadow-sm" 
                            id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        
                        <!-- Toggle Password Visibility -->
                        <button class="absolute inset-y-0 right-0 pr-4 flex items-center text-secondary hover:text-primary transition-colors focus:outline-none" type="button" onclick="const p=document.getElementById('password'); p.type=p.type==='password'?'text':'password';">
                            <span class="material-symbols-outlined text-[20px]" data-icon="visibility">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-5 rounded-xl bg-primary hover:bg-primary-container text-white font-headline font-bold text-lg shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                        <span>Masuk Sistem</span>
                        <span class="material-symbols-outlined text-[20px]" data-icon="login">login</span>
                    </button>
                </div>
            </form>

            <!-- Footer Text -->
            <div class="mt-8 pt-6 border-t border-outline-variant/20 text-center">
                <p class="text-xs text-secondary font-medium">Bermasalah saat masuk? 
                    <a href="#" class="text-primary font-bold hover:underline">Hubungi Administrator</a>
                </p>
            </div>
        </div>

        <!-- Right Side: Aesthetic Hero (Hidden on Mobile) -->
        <div class="hidden md:block md:w-1/2 lg:w-7/12 bg-login-hero relative overflow-hidden bg-surface-container" data-alt="Indoor mushroom farm view">
            
            <!-- Elegant Fog / Ambient Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-tr from-[#002201]/95 via-[#2b5825]/60 to-[#a1d494]/10 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

            <!-- Floating Decoration -->
            <div class="absolute top-8 right-8 z-20">
                <div class="flex flex-col items-end">
                    <span class="text-white text-xs font-bold uppercase tracking-widest mb-1 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20 shadow-sm">Mycology OS v2.0</span>
                    <span class="text-white font-headline font-extrabold text-2xl drop-shadow-lg">The Digital Greenhouse</span>
                </div>
            </div>

            <!-- Glassmorphism Stats Overlay -->
            <div class="absolute bottom-10 left-10 right-10 flex gap-4 z-20 lg:flex-row flex-col">
                <!-- Temp Card -->
                <div class="flex-1 backdrop-blur-2xl bg-white/10 p-5 rounded-2xl border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.4)] hover:bg-white/20 transition-all cursor-default">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-error/90 text-white flex items-center justify-center shadow-inner">
                            <span class="material-symbols-outlined text-[16px]">thermostat</span>
                        </div>
                        <span class="text-white font-bold text-sm tracking-wide drop-shadow-md">Suhu</span>
                    </div>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-headline font-black text-white drop-shadow-lg">24.5°</span>
                        <span class="text-white/80 font-bold text-lg">C</span>
                    </div>
                </div>

                <!-- Humidity Card -->
                <div class="flex-1 backdrop-blur-2xl bg-white/10 p-5 rounded-2xl border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.4)] hover:bg-white/20 transition-all cursor-default">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-tertiary-container/90 text-white flex items-center justify-center shadow-inner">
                            <span class="material-symbols-outlined text-[16px]">humidity_mid</span>
                        </div>
                        <span class="text-white font-bold text-sm tracking-wide drop-shadow-md">Kelembapan</span>
                    </div>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-headline font-black text-white drop-shadow-lg">88</span>
                        <span class="text-white/80 font-bold text-lg">%</span>
                    </div>
                </div>

                <!-- CO2 Card -->
                <div class="flex-1 backdrop-blur-2xl bg-white/10 p-5 rounded-2xl border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.4)] hover:bg-white/20 transition-all cursor-default">
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-secondary/90 text-white flex items-center justify-center shadow-inner">
                            <span class="material-symbols-outlined text-[16px]">co2</span>
                        </div>
                        <span class="text-white font-bold text-sm tracking-wide drop-shadow-md">Karbon</span>
                    </div>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-headline font-black text-white drop-shadow-lg">840</span>
                        <span class="text-white/80 font-bold text-lg">ppm</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Background Ambient Decorative Circles -->
    <div class="fixed -top-32 -left-32 w-[500px] h-[500px] bg-primary-fixed/30 rounded-full blur-[100px] -z-10 animate-pulse" style="animation-duration: 8s;"></div>
    <div class="fixed -bottom-32 -right-32 w-[500px] h-[500px] bg-tertiary-fixed/30 rounded-full blur-[100px] -z-10"></div>
</x-guest-layout>
