<x-card shadow class="mx-auto w-[32rem]">
  <x-form wire:submit="submit">
    <x-input label="Seu nome completo" wire:model="name" />
    <x-input label="Seu email" wire:model="email" />
    <x-input label="Confirme seu email" wire:model="email_confirmation" />
    <x-input label="Senha" wire:model="password" type="password" />

    <x-slot:actions>
    <div class="w-full flex items-center justify-between">
        <a wire:navigate href="{{ route('login') }}" class="link link-primary">Já tenho uma conta</a>
        <div class="flex gap-4">
          <x-button label="Limpar campos" type="reset" />
          <x-button label="Cadastrar" class="btn-primary" type="submit" spinner="save" />
        </div>
      </div>
    </x-slot:actions>
  </x-form>
</x-card>
