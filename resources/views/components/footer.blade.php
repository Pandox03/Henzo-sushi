<footer class="bg-gray-800 text-white py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Restaurant Info -->
            <div class="sm:col-span-2 lg:col-span-2">
                <h3 class="text-xl sm:text-2xl font-bold text-yellow-400 mb-3 sm:mb-4">🍣 Henzo Sushi</h3>
                <p class="text-gray-300 mb-4 text-sm sm:text-base leading-relaxed">
                    Experience the finest Japanese cuisine with authentic flavors and traditional techniques. 
                    We bring you the best of Japan right to your table.
                </p>
                <div class="flex space-x-3 sm:space-x-4">
                    <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors text-lg sm:text-xl" title="Facebook">📘</a>
                    <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors text-lg sm:text-xl" title="Instagram">📷</a>
                    <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors text-lg sm:text-xl" title="Twitter">🐦</a>
                    <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors text-lg sm:text-xl" title="TikTok">🎵</a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-yellow-400 transition-colors text-sm sm:text-base">Home</a></li>
                    <li><a href="#menu" class="text-gray-300 hover:text-yellow-400 transition-colors text-sm sm:text-base">Menu</a></li>
                    <li><a href="#about" class="text-gray-300 hover:text-yellow-400 transition-colors text-sm sm:text-base">About Us</a></li>
                    <li><a href="#contact" class="text-gray-300 hover:text-yellow-400 transition-colors text-sm sm:text-base">Contact</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-yellow-400 transition-colors text-sm sm:text-base">Dashboard</a></li>
                    @endauth
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Contact Info</h4>
                <div class="space-y-2 text-gray-300 text-sm sm:text-base">
                    <p class="flex items-start"><span class="mr-2">📍</span> 123 Sushi Street, Tokyo District</p>
                    <p class="flex items-center"><span class="mr-2">📞</span> +1 (555) 123-SUSHI</p>
                    <p class="flex items-center"><span class="mr-2">✉️</span> info@henzosushi.com</p>
                    <p class="flex items-center"><span class="mr-2">🕒</span> Mon-Sun: 11:00 AM - 10:00 PM</p>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-gray-400 text-sm sm:text-base">
            <p>&copy; {{ date('Y') }} Henzo Sushi. All rights reserved. Made with ❤️ for sushi lovers.</p>
        </div>
    </div>
</footer>
