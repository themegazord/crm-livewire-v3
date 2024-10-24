<x-card shadow class="mx-auto w-[32rem]" title="Recuperar senha">
  @if ($errors->hasAny(['rateLimiter']))
    <x-alert icon="o-exclamation-triangle" class="alert-warning mb-4">
      @error('rateLimiter')
      <span>{{ $message }}</span>
      @enderror
    </x-alert>
  @endif
  <x-form wire:submit="submit">
    <x-input label="Insira seu email" wire:model="email"/>

    <x-slot:actions>
      <x-button label="Enviar email" class="btn-primary" type="submit" spinner="save"/>
    </x-slot:actions>
  </x-form>
</x-card>

