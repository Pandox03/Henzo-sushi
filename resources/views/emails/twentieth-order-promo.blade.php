<x-mail::message>
# 🎊 VIP Status Unlocked! 20% Off Your Next Order

Wow! 20 orders - you're officially a VIP member! As a special thank you for your loyalty, here's an exclusive discount:

<x-mail::panel>
    Promo Code: **SUSHI20**
    
    Enjoy **20% off** on your next order!
    
    *Valid for your next order only*
</x-mail::panel>

<x-mail::button :url="route('products.index')" color="success">
    Shop Now
</x-mail::button>

With gratitude,<br>
The {{ config('app.name') }} Team

<x-mail::footer>
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
</x-mail::footer>
</x-mail::message>
