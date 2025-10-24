<x-mail::message>
# Welcome to {{ config('app.name') }}!

Thank you for your first order with us! We're excited to have you as our customer.

As a token of our appreciation, here's a special welcome discount for your next order:

<x-mail::panel>
    Promo Code: **WELCOME**
    
    Get **15% off** on your next order!
    
    *Valid for your next order only*
</x-mail::panel>

<x-mail::button :url="route('products.index')" color="success">
    Start Shopping Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

<x-mail::footer>
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    
    If you did not create an account, no further action is required.
</x-mail::footer>
</x-mail::message>
