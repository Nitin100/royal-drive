<x-mail::message>
# New Contact Enquiry

A new enquiry has been submitted from the contact page.

<strong>Name:</strong> {{ $name }}  
<strong>Email:</strong> {{ $email }}  
<strong>Phone:</strong> {{ $phone ?? 'Not provided' }}  
<strong>Subject:</strong> {{ $subject ?? 'General enquiry' }}

<strong>Message:</strong>

{{ $message }}

Thanks,
{{ config('app.name') }}
</x-mail::message>
