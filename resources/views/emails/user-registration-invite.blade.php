<x-mail::message>
# Complete Your Registration

Hello {{ $name }},

You have been invited to complete your registration. Please click the button below to proceed.

<x-mail::button :url="$url">
Complete Registration
</x-mail::button>

This link will expire in 7 days.

If you did not expect this invitation, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>