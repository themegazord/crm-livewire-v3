<x-card shadow class="mx-auto w-[32rem]" title="Recuperar senha">
  <x-form wire:submit="submit">
    <x-input label="Seu email" value="Seu email" readonly wire:model="email" />
    <x-password label="Insira sua nova senha" hint="Clique no olho para verificar sua senha" wire:model="password" clearable />
    <x-password label="Confirme sua nova senha" hint="Clique no olho para verificar sua senha" wire:model="password_confirmation" clearable />

    <x-slot:actions>
      <x-button label="Trocar a senha" class="btn-primary" type="submit" spinner="save" />
    </x-slot:actions>
  </x-form>
</x-card>
