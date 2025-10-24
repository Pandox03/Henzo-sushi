<x-mail::message>
# Congratulations on Your 10th Order! 🎉

Thank you for being a loyal customer! As a token of our appreciation, here's a special discount for your next order:

<x-mail::panel>
    Promo Code: **SUSHI10**
    
    Enjoy **10% off** on your next order!
    
    *Valid for your next order only*
</x-mail::panel>

<x-mail::button :url="route('products.index')" color="success">
    Shop Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

<x-mail::footer>
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
</x-mail::footer>
</x-mail::message>
