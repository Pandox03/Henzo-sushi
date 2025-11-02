<x-mail::message>
# {{ $promoCode->name }} 🎉

@if($promoCode->description)
{{ $promoCode->description }}
@else
We have a special offer just for you!
@endif

<x-mail::panel>
    **Promo Code: {{ $promoCode->code }}**
    
    @if($promoCode->discount_type === 'percentage')
        Get **{{ $promoCode->discount_value }}% off**
        @if($promoCode->applicable_products)
            on selected items
        @else
            on your order
        @endif
        !
    @else
        Get **{{ number_format($promoCode->discount_value, 2) }} MAD off**
        @if($promoCode->applicable_products)
            on selected items
        @else
            on your order
        @endif
        !
    @endif
    
    @if($promoCode->expires_at)
        *Valid until {{ $promoCode->expires_at->format('F d, Y') }}*
    @endif
    
    @if($promoCode->usage_limit_per_user > 1)
        *Can be used {{ $promoCode->usage_limit_per_user }} times*
    @else
        *One-time use only*
    @endif
</x-mail::panel>

<x-mail::button :url="route('products.index')" color="success">
    Shop Now & Use Code
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} Team

<x-mail::footer>
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
</x-mail::footer>
</x-mail::message>