<x-mail::message>
# {{__('auth.welcome', ['name' => $user->name]) }}!!

<x-mail::button :url="$url" color="success">
Button Text
</x-mail::button>

<x-mail::panel>
This is a panel content
</x-mail::panel>

## Table component:

<x-mail::table>
| Laravel       | Table         | Example |
|:------------- |:-------------:| -------:|
| Col 2 is      | Centered      | $10     |
| Col 3 is      | Right-Aligned | $20     |
</x-mail::table>

<x-mail::subcopy>
This is a subcopy component
</x-mail::subcopy>

Thanks,
{{ config('app.name') }}
</x-mail::message>
