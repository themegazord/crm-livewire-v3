<div>
  <x-button icon="o-trash" @click="$wire.modal = true" spinner class="btn-sm"></x-button>
  <x-modal wire:model="modal" title="Confirmação de inativação do cadastro" subtitle="Você está inativando o cadastro do(a) {{ $usuario->name }}" separator class="backdrop-blur">

    @error('confirmacao')
    <x-alert icon="o-exclamation-triangle" class="alert-error mb-4">
      {{ $message }}
    </x-alert>
    @enderror

    <div class="mb-5">
      <x-input label="Escreva `{{ $usuario->name }}` para confirmar a inativação do usuário." wire:model="confirmacao_confirmation" />
    </div>


    <x-slot:actions>
      <x-button label="Cancelar" @click="$wire.modal = false" />
      <x-button label="Inativar" class="btn-primary" wire:click="destroy" />
    </x-slot:actions>
  </x-modal>
</div>
