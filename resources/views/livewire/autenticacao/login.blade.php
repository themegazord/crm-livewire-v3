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
      <x-button label="Limpar campos" type="reset" />
      <x-button label="Logar" class="btn-primary" type="submit" spinner="save" />
    </x-slot:actions>
  </x-form>
</x-card>
