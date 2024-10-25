<x-card shadow class="mx-auto w-[32rem]" title="Login">
  @if ($errors->hasAny(['rateLimiter']))
  <x-alert icon="o-exclamation-triangle" class="alert-warning mb-4">
    @error('rateLimiter')
    <span>{{ $message }}</span>
    @enderror
  </x-alert>
  @endif
  <x-form wire:submit="submit">
    <x-input label="Insira seu email" wire:model="email" />
    <x-input label="Insira sua senha" wire:model="password" type="password" password-icon="o-lock-closed" password-visible-icon="o-lock-open" clearable />


    <x-slot:actions>
      <div class="w-full flex items-center justify-between">
        <div class="flex flex-col gap-2">
          <a wire:navigate href="{{ route('autenticacao.registro') }}" class="link link-primary">Cadastre-se</a>
          <a wire:navigate href="{{ route('password.reset') }}" class="link link-primary">Esqueci minha senha</a>
        </div>
        <div class="flex gap-4">
          <x-button label="Limpar campos" type="reset" />
          <x-button label="Logar" class="btn-primary" type="submit" spinner="save" />
        </div>
      </div>
    </x-slot:actions>
  </x-form>
</x-card>
