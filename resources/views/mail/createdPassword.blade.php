Здравствуйте, <i>{{ $createdPasswordData->receiver }}</i>!
<div>
    <p>Ваш логин для входа в систему: <b>{{ $createdPasswordData->email }}</b></p>
    <p>Ваш сгенерированный пароль: <b>{{ $createdPasswordData->password }}</b></p>
    <p>При необходимости, Вы сможете изменить его в разделе «Настройки безопасности» после авторизации в системе.</p>
</div>
