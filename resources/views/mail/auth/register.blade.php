<x-mail::message>

<p>Bonjour {{$user->name}},</p>

<p>Merci de vous être inscrit sur {{ config('app.name') }} ! Nous sommes ravis de vous accueillir dans notre communauté.</p>

<p>
    Voici quelques informations pour vous aider à démarrer :
    <ol>
        <li><b>Votre nom d'utilisateur </b> : {{ $user->name }}</li>
        <li><b>Adresse e-mail</b>: {{ $user->email }}</li>
    </ol>
</p>


<p>Pour explorer notre site, connectez-vous avec vos identifiants ici : <a  target="_blank" href="{{ config('app.bravorh_front_url') }}">BravoRH</a></p>

<p>N'hésitez pas à nous contacter si vous avez des questions ou des suggestions. Votre avis nous tient à cœur !</p>

<p>À très bientôt sur {{ config('app.name') }} !</p>
<b>{{ $user->account->name }}</b>

</x-mail::message>
