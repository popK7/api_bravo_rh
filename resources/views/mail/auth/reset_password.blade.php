<x-mail::message>

<p>Bonjour {{$user->name}},</p>

<p>Nous avons reçu une demande pour changer votre mot de passe. Si c'est vous qui avez fait cette demande, veuillez suivre les instructions ci-dessous pour réinitialiser votre mot de passe :</p>

<ol>
    <li>Cliquez sur le lien suivant : <a href="{{$url}}">ici</a></li>
    <li>Suivez les instructions pour créer un nouveau mot de passe.</li>
</ol>

<p>Si vous n'avez pas demandé de changement de mot de passe, veuillez ignorer cet e-mail. Votre compte reste sécurisé.</p>

<p>Cordialement,</p>
<b>{{$user->account->name}}</b>
<p></p>

<x-mail::button :url="$url">

</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
