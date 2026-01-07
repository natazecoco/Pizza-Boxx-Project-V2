<footer class="bg-[#131a26] text-gray-300 pt-12 pb-6 border-t-4 border-red-600">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/pizza-boxx-logo.png') }}" alt="Pizza Boxx Logo" class="h-10 w-10">
                    <span class="text-2xl font-bold text-red-400">Pizza Boxx</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Authentic Italian pizza made with love and the freshest ingredients since 2010.
                </p>
                <div class="flex items-center gap-4 text-lg pt-2">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-lg text-white mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-red-400 transition-colors">Home</a></li>
                    <li><a href="{{ route('menu.index') }}" class="hover:text-red-400 transition-colors">Menu</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-red-400 transition-colors">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-red-400 transition-colors">Contact</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-red-400 transition-colors">My Cart</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-lg text-white mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-red-400 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-red-400 transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-red-400 transition-colors">Refund Policy</a></li>
                    <li><a href="#" class="hover:text-red-400 transition-colors">Delivery Policy</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-lg text-white mb-4">Contact Us</h3>
                <address class="not-italic space-y-2">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt mt-1 text-red-400"></i>
                        <span>123 Pizza Street, Sukahati, Indonesia</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone text-red-400"></i>
                        <a href="tel:+6281234567890" class="hover:text-red-400 transition-colors">+62 812 3456 7890</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-envelope text-red-400"></i>
                        <a href="mailto:info@pizzaboxx.com" class="hover:text-red-400 transition-colors">info@pizzaboxx.com</a>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-red-400"></i>
                        <span>Open Daily: 10:00 - 22:00</span>
                    </div>
                </address>
            </div>
        </div>

        <div class="border-t border-gray-700 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} Pizza Boxx. All rights reserved.
            </div>
        </div>
    </div>
</footer>