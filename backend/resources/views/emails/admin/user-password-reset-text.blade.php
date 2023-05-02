パスワード再設定のリクエストを受け付けました。

以下のURLからパスワードの再設定を行ってください。

{{ route('admin.auth.reset-password', ['token' => $user->token]) }}
※このURLの有効期限は {{ $user->token_expired_at }} までです。期限を越えた場合は再度再発行のリクエストを送信してください。

@include('emails.common.signature-text')
